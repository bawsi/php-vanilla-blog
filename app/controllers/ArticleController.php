<?php

use Intervention\Image\ImageManagerStatic as Image;
use \Firebase\JWT\JWT;

class ArticleController
{
    private $articleModel;
    private $msg;
    private $userController;
    /**
     * Set property $db to argument, which
     * has to be an instance of Db object
     *
     * @param Db $db Object Db
     */
    public function __construct(Article $articleModel, $msg, $userController)
    {
        $this->articleModel = $articleModel;
        $this->msg = $msg;
        $this->userController = $userController;
    }

    /**
     * Get custom number of articles from database, newest first
     *
     * @param  int $numberOfArticles number of articles
     *
     * @return array                 Array of all articles
     */
    public function getArticles($numberOfArticles)
    {
        return $this->articleModel->getArticles($numberOfArticles);
    }


    /**
     * Grabs article by its ID ($_GET['id']), validates it,
     * and then returns that article
     *
     * @return array Associative array of articles
     */
    public function getArticleById()
    {
        // Sanitizing ID value
        $articleId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        return $this->articleModel->getSingleArticleById($articleId);
    }


    /**
     * Checks if submitted new article form data is valid.
     * For now, it only checks if everything was filled in
     * @return Array Redirects on POST request, or returns array of categories on GET
     */
    public function validateAndStoreArticle()
    {
        // If POST request, execute this method
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get all the article data and sanitize
            $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        	$body = $_POST['body'];
        	$categoryId = filter_input(INPUT_POST, 'categoryId', FILTER_SANITIZE_NUMBER_INT);
        	$image = $_FILES['image'];
        	$authorId = $this->userController->getUserId();

            // Basic validation
            if (!empty($title) && !empty($body) && $authorId && !empty($categoryId))
            {
                // Storing data (except img) to db
                if (($articleId = $this->articleModel->saveArticle($title, $body, $categoryId, $authorId)))
                {
                    // If image was submitted, get its info, resize it, and store it to /uploads directory
                    if (getimagesize($image['tmp_name']))
                    {
                        // Getting basic img info from submitted img
                        $imageName = $image['name'];
                        $imageTmpName = $image['tmp_name'];
                        $imageFileType = $image['type'];

                        // Getting img extension
                        $imageExtension = explode('.', $imageName);
                        $ImageActualExtension = strtolower(end($imageExtension));

                        // Supported img formats
                        $allowed = ['jpg', 'jpeg', 'png'];

                        // If submitted image has extension which is not allowed, redirect back with error
                        if(!in_array($ImageActualExtension, $allowed)) {
                            $this->msg->error('Only .jpg, .jpeg and .png images allowed', '/admin/new-article.php');
                            die();
                        }

                        // Saving the image - cant save above, because I cant get article's ID before article is saved
                        Image::configure(['driver' => 'imagick']);

                        // Setting new image file name and paths.
                        // One path for storing actual img, and one for linking to img from db
                        $fileName = $articleId . '_400x200_' . uniqid('', true) . '.' . $ImageActualExtension;
                        $imgFullPath = PUBLIC_PATH . '/uploads' . '/' . $fileName;
                        $imgPathForDb = '/uploads/' . $fileName;

                        // Resize and save image to public/uploads/
                        Image::make($imageTmpName)->fit(400, 200)->save($imgFullPath);

                        // Save path of image to article db
                        $this->articleModel->saveArticleImagePath($imgPathForDb, $articleId);
                    }

                    // Set success msg and redirect to article
                    $this->msg->success('Article added to database!', '/article.php?id=' . $articleId);
                    die();

                } else {
                    // Set error message, and redirect back to new article page
                    $this->msg->error('Failed to store article or image to database. Try again!', '/admin/new-article.php');
                    die();
                }
            } else {
                // Set error message, and redirect back to new article page
                $this->msg->error('All fields except image are required.', '/admin/new-article.php');
                die();
            }
        } else {
            // Return array of all categories for form
            return $this->getCategories();
        }
    }

    /**
     * Edits article with updated data
     *
     * @return Array Returns either array, or just redirects
     */
    public function edit()
    {
        // If request method is not POST, return single article
        // data from its id, and list of all categories
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $articleData = $this->getArticleById();
            $categories = $this->getCategories();

            return ['article' => $articleData, 'categories' => $categories];
        }

        // Getting POSTED article data
        $articleId = $_POST['articleId'];
        $title = $_POST['title'];
        $body = $_POST['body'];
        $image = $_FILES['image'];
        $categoryId = $_POST['categoryId'];



        // Basic validation
        if (!empty($title) && !empty($body) && !empty($articleId) && !empty($categoryId))
        {
            // Filtering out any unwanted characters
            $title = filter_var($title, FILTER_SANITIZE_STRING);
            $body = $body;
            $articleId = filter_var($articleId, FILTER_SANITIZE_NUMBER_INT);
            $image = $image;
            $authorId = $this->userController->getUserId();

            // Saving article
            $this->articleModel->edit($articleId, $title, $body, $categoryId);

            // If file was uploaded, remove old file, resize uploaded file, and save it
            if (file_exists($image['tmp_name']))
            {
                // Getting name of old image name, so we can delete it later
                $oldImagePath = $this->articleModel->getSingleArticleById($articleId);
                $oldImagePath = ($oldImagePath['img_path'] == '/uploads/default.png') ? false : '/var/www/code/public' . $oldImagePath['img_path'];

                // Getting Image info
                $imageName = $image['name'];
                $imageTmpName = $image['tmp_name'];
                $imageFileType = $image['type'];

                // Getting original image extension
                $imageExtension = explode('.', $imageName);
                $ImageActualExtension = strtolower(end($imageExtension));

                // Allowed image extensions
                $allowed = ['jpg', 'jpeg', 'png'];

                // If submitted image has extension which is not allowed, redirect back with error
                if(!in_array($ImageActualExtension, $allowed)) {
                    $this->msg->error('Only .jpg, .jpeg and .png images allowed', '/admin/edit.php?id=' . $articleId);
                    die();
                }

                // Configuring Intervention
                Image::configure(['driver' => 'imagick']);

                // Setting up new random image name + storage path
                $fileName = $articleId . '_400x200_' . uniqid('', true) . '.' . $ImageActualExtension;
                $imgFullPath = PUBLIC_PATH . '/uploads' . '/' . $fileName;
                $imgPathForDb = '/uploads/' . $fileName;

                // Resizing image to 400x200px, and storing it to /uploads
                Image::make($imageTmpName)->fit(400, 200)->save($imgFullPath);

                // Saving path of image to articles table
                $this->articleModel->saveArticleImagePath($imgPathForDb, $articleId);

                // If old image is NOT default.png, delete it
                ($oldImagePath !== false) ? unlink($oldImagePath) : '';
            }

        // Redirect to edited article
        $this->msg->success('Article successfully updated!', '/article.php?id=' . $articleId);
        die();

    } else {
        // If any of the fields (title, body, authorId or categoryId) are empty,
        // set error message, and redirect back to edit page
        $this->msg->error('All fields except image are required!', '/admin/edit.php?id=' . $articleId);
        die();
    }
}


    /**
     * Get list of all the categories
     *
     * @return Array List of all categories
     */
    public function getCategories()
    {
        $categories = $this->articleModel->getCategories();

        return $categories;
    }


    /**
     * Delete articles from database + image that is
     * associated with that article, if it was uploaded.
     * Once deleted, redirec to /admin/article-index.php
     *
     * @param  int $id id of article to delete
     */
    public function delete()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        // Get article info, before deleting it, so I can delete old img
        $oldImagePath = $this->articleModel->getSingleArticleById($id);
        $oldImagePath = ($oldImagePath['img_path'] == '/uploads/default.png') ? false : '/var/www/php-blog/public' . $oldImagePath['img_path'];

        // Delete article
        chmod($oldImagePath, 0707);
        $deletedStatus = $this->articleModel->delete($id);

        // If old image is NOT default.png, delete it
        ($oldImagePath !== false) ? unlink($oldImagePath) : '';

        // Redirect to article index, once article is deleted
        $this->msg->info('Article with ID of ' . $id . ' successfully deleted!', '/admin/article-index.php');
        die();
    }

    /**
     * Return number of articles that $perPage specifies. If $_GET['c']
     * is set, get articles only from that category, otherwise, from all categories.
     * If $_GET['p'] is set, get articles with an offset, otherwise, get first page
     *
     * @param  int $perPage How many results we want per page
     *
     * @return array        Array containing articles, numberOfPages, page and selectedCategory
     */
    public function getArticlesPaginated($perPage)
    {
        // If category is set, get paginated articles from specific category
        if (isset($_GET['c']) || !empty($_GET['c'])) {
            // pagination and getting articles
            $categoryName = filter_input(INPUT_GET, 'c', FILTER_SANITIZE_STRING);

            // Get category's ID from its name
            $categoryId = $this->articleModel->getCategoryIdFromName($categoryName);

        } else {
            // If category is not set, set both categoryId and categoryName to false
            $categoryId = false;
            $categoryName = false;
        }

        // if GET 'p' is set, set $page equal to its value, otherwise, set it to 1
        $page = (isset($_GET['p'])) ? filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT) : 1;

        // Get total number of pages, from either specifc category, or from all categories
        $numOfArticles = $this->articleModel->getTotalNumberOfArticles($categoryId);
        $numOfPages = ceil($numOfArticles[0] / $perPage);

        // Getting articles
        $articles = $this->articleModel->paginate($page, $perPage, $categoryId);

        return [
            'articles'         => $articles,
            'numOfPages'       => $numOfPages,
            'page'             => $page,
            'selectedCategory' => $categoryName
        ];

    }


    /**
     * Search 'articles' table for keyword provided via $_GET['S']
     * If cant find any results, redirect to homepage
     *
     * @return Array       Array of matching articles
     */
    public function search()
    {
        // If search variable is not set, or empty, redirect to homepage
        if (!isset($_GET['s']) || empty($_GET['s'])) {
            $this->msg->error('Cannot access this page, without entering a search term.', '/');
            die();
        }

        // Adding wildcard (%) signs to search term, so I can bind in sql stmt
        $searchTerm = '%' . $_GET['s'] . '%';

        // Search db using Article model
        $articles = $this->articleModel->search($searchTerm);

        // If any articles were found, return them, otherwise, set error msg, and redirect to homepage
        if ($articles) {
            return $articles;
        } else {
            $this->msg->error('No results were found for that search query.', '/');
            die();
        }

    }

}
