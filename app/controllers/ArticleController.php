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
        $isIdInt = filter_var($id, FILTER_VALIDATE_INT);

        if ($isIdInt) {
            return $this->articleModel->getSingleArticleById($id);
        }
        else {
            return false;
        }

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
    public function validateAndStoreArticle($title, $body, $articleCategory, $image, $authorId)
    {
        // Very basic validation
        if (!empty($title) && !empty($body) && !empty($image) && is_int($authorId)) {

            // Getting Image info
            $imageName = $image['name'];
            $imageTmpName = $image['tmp_name'];
            $imageFileType = $image['type'];

            $imageExtension = explode('.', $imageName);
            $ImageActualExtension = strtolower(end($imageExtension));

            $allowed = ['jpg', 'jpeg', 'png'];

            // Validating image and setting paths etc
            if (in_array($ImageActualExtension, $allowed)) {
                $imageNameNew = uniqid('', true) . '.' . $ImageActualExtension;
                $imageDest = PUBLIC_PATH . '/uploads/' . $imageNameNew;
            } else {
                $_SESSION['error_messages'][] = 'Only .jpg, .jpeg and .png images allowed';
                header('location: /admin/new-article.php');
            }

            // Storing data to db
            if (($articleId = $this->articleModel->saveArticle($title, $body, $articleCategory, $authorId)) && move_uploaded_file($imageTmpName, $imageDest)) {
                // Saving images - cant save above, because I cant get article's ID before article is saved
                $uploadsPath = PUBLIC_PATH . '/uploads';
                Image::configure(['driver' => 'imagick']);
                $imgSmall = Image::make($imageDest)->fit(400, 200)->save($uploadsPath . '/' . $articleId . '_400x200_' . $imageNameNew);
                $imgBig = Image::make($imageDest)->fit(935, 400)->save($uploadsPath . '/' . $articleId . '_935x400_' . $imageNameNew);

                $this->articleModel->saveArticleImagesPaths($imgSmall, $imgBig);

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

    public function getCategories() {
        $categories = $this->articleModel->getCategories();

        return $categories;
    }

    public function edit($articleId, $title, $body, $categoryId) {
        return $this->articleModel->edit($articleId, $title, $body, $categoryId);
    }

    public function delete($id) {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return $this->articleModel->delete($id);
    }

    public function getArticlesPaginated($page, $perPage, $category = 'all') {
        $articles = $this->articleModel->paginate($page, $perPage);
        return $articles;
    }

    public function getTotalNumberOfPages($perPage) {
        $numOfArticles = $this->articleModel->getTotalNumberOfArticles();
        $numOfPages = ceil($numOfArticles[0] / $perPage);
        return $numOfPages;
    }

}
