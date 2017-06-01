<?php

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
    public function validateAndStoreArticle($title, $body, $authorId)
    {
        if (!empty($title) && !empty($body) && is_int($authorId)) {
            if ($this->articleModel->saveArticle($title, $body, $authorId)) {
                return true;
            }
            else {
                return false;
            }
        } else {
            return false;
        }
    }
}
