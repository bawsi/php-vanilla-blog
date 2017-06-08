<?php

use Intervention\Image\ImageManagerStatic as Image;

class ArticleController
{
    private $articleModel;
    /**
     * Set property $db to argument, which
     * has to be an instance of Db object
     *
     * @param Db $db Object Db
     */
    public function __construct(Article $articleModel)
    {
        $this->articleModel = $articleModel;
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
     * Grabs article by its ID, validates it,
     * and then returns that article
     *
     * @param  int $id ID of article
     *
     * @return array Associative array of articles
     */
    public function getArticleById($id)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        return $this->articleModel->getSingleArticleById($id);
    }

    /**
     * Checks if submitted new article form data is valid.
     * For now, it only checks if everything was filled in
     *
     * @param  string $title    Article title
     * @param  string $body     Article body
     * @param  int    $authorId ID of author
     *
     * @return bool             Return true, if all fields were filled, false otherwise
     */
    public function validateAndStoreArticle($title, $body, $articleCategoryId, $image, $authorId)
    {
        // Basic validation
        if (!empty($title) && !empty($body) && is_int($authorId))
        {
            // Filtering out any unwanted characters
            $title = filter_input($title, FILTER_SANITIZE_STRING);
            $body = $body;
            $articleCategoryId = filter_var($articleCategoryId, FILTER_SANITIZE_NUMBER_INT);
            $image = $image;
            $authorId = filter_var($authorId, FILTER_SANITIZE_NUMBER_INT);

            // Storing data (except img) to db
            if (($articleId = $this->articleModel->saveArticle($title, $body, $articleCategoryId, $authorId)))
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
                        $_SESSION['error_messages'][] = 'Only .jpg, .jpeg and .png images allowed';
                        header('location: /admin/new-article.php');
                        die();
                    }

                    // Saving the image - cant save above, because I cant get article's ID before article is saved
                    Image::configure(['driver' => 'imagick']);

                    // Setting new image file name and paths.
                    // One path for storing actual img, and one for linking to img form db
                    $fileName = $articleId . '_400x200_' . uniqid('', true) . '.' . $ImageActualExtension;
                    $imgFullPath = PUBLIC_PATH . '/uploads' . '/' . $fileName;
                    $imgPathForDb = '/uploads/' . $fileName;

                    // Resize and save image to public/uploads/
                    Image::make($imageTmpName)->fit(400, 200)->save($imgFullPath);

                    // Save path of image to article db
                    $this->articleModel->saveArticleImagePath($imgPathForDb, $articleId);
                }

                // Set success msg and return article id
                $_SESSION['success_messages'][] = 'Article added to database!';
                return $articleId;

            } else {
                $_SESSION['error_messages'][] = 'Failed to store article or image to database.. try again!';
                return false;
            }
        } else {
            $_SESSION['error_messages'][] = 'All fields are required!';
            return false;
        }
    }

    public function getCategories()
    {
        $categories = $this->articleModel->getCategories();

        return $categories;
    }


    public function edit($articleId, $title, $body, $image, $categoryId)
    {
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

            $imageExtension = explode('.', $imageName);
            $ImageActualExtension = strtolower(end($imageExtension));

            $allowed = ['jpg', 'jpeg', 'png'];

            // Configuring Intervention, and setting up new random image name + storage path
            Image::configure(['driver' => 'imagick']);

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

        return true;
    }

    public function delete($id)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        // Get article info, before deleting it, so I can delete old img
        $oldImagePath = $this->articleModel->getSingleArticleById($id);
        $oldImagePath = ($oldImagePath['img_path'] == '/uploads/default.png') ? false : '/var/www/code/public' . $oldImagePath['img_path'];

        // Delete article
        $deletedStatus = $this->articleModel->delete($id);

        // If old image is NOT default.png, delete it
        ($oldImagePath !== false) ? unlink($oldImagePath) : '';

        return $deletedStatus;
    }

    public function getArticlesPaginated($page, $perPage, $category = -1)
    {
        if ($category !== -1) {
            $category = $this->articleModel->getCategoryIdFromName($category);
        }

        $articles = $this->articleModel->paginate($page, $perPage, $category['id']);
        return $articles;
    }

    public function getTotalNumberOfPages($perPage, $category = -1)
    {
        if ($category !== -1) {
            $category = $this->articleModel->getCategoryIdFromName($category);
        }

        $numOfArticles = $this->articleModel->getTotalNumberOfArticles($category['id']);
        $numOfPages = ceil($numOfArticles[0] / $perPage);

        return $numOfPages;
    }

    public function search()
    {
        // If search variable is not set, or empty, redirect to homepage
        if (!isset($_GET['s']) || empty($_GET['s'])) {
            header('location: /');
        }

        // Adding wildcard (%) signs to search term, so I can bind in sql stmt
        $searchTerm = '%' . $_GET['s'] . '%';

        $articles = $this->articleModel->search($searchTerm);

        // If any articles were found, return them, otherwise, set error msg, and redirect to homepage
        if ($articles) {
            return $articles;
        } else {
            $_SESSION['error_messages'][] = 'No results were found for that search query.';
            header('location: /');
        }

    }

}
