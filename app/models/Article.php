<?php

class Article
{
    private $db;

    /**
     * Set property $db to argument, which
     * has to be an instance of Db object
     *
     * @param Db $db Object Db
     */
    public function __construct(Db $db)
    {
        $this->db = $db->getConnection();
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
        $stmt = $this->db->prepare(
            'SELECT articles.id, articles.title, articles.body, articles.created_at,
				    users.username AS author, article_categories.category_name
			 FROM articles
			 JOIN users ON articles.author_id = users.id
			 JOIN article_categories ON articles.category_id = article_categories.id
			 ORDER BY articles.id DESC
			 LIMIT :numberOfArticles
		    ');

        $stmt->bindParam(':numberOfArticles', $numberOfArticles, PDO::PARAM_INT);
        $stmt->execute();
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $articles;
    }

    /**
     * Returns single article by its ID
     *
     * @param  int $id ID of article
     *
     * @return array Associative array of articles
     */
    public function getSingleArticleById($id)
    {
        $stmt = $this->db->prepare(
            'SELECT articles.title, articles.body, articles.created_at,
	                article_categories.category_name, users.username as author
			FROM articles
			JOIN article_categories ON articles.category_id = article_categories.id
			JOIN users ON articles.author_id = users.id
			WHERE articles.id = :id
			LIMIT 1
	    ');

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        return $article;
    }

    /**
     * Save article to database
     *
     * @param  string $title    Article title
     * @param  string $body     Article body
     * @param  int    $authorId ID of author
     *
     * @return bool               Return true, if all fields were filled, false otherwise
     */
    public function saveArticle($title, $body, $articleCategory, $authorId)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO articles
			      (title, body, author_id, created_at, category_id)
			      VALUES (:title, :body, :authorId, :createdAt, :category_id)
			  ');

        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':body', $body, PDO::PARAM_STR);
        $stmt->bindParam(':authorId', $authorId, PDO::PARAM_INT);
        $currentTime = time();
        $stmt->bindParam(':createdAt', $currentTime, PDO::PARAM_STR);
        $stmt->bindParam(':category_id', $articleCategory);
        $stmt->execute();

        return ($stmt) ? $this->db->lastInsertId() : false;
    }

    public function getCategories() {
        $stmt = $this->db->query('SELECT * FROM article_categories');
        $stmt->execute();

        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $categories;
    }

    public function edit($articleId, $title, $body, $categoryId) {
        $stmt = $this->db->prepare(
            'UPDATE articles
            SET title = :title, body = :body, category_id = :categoryId
            WHERE id = :id'
        );
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':body', $body, PDO::PARAM_STR);
        $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':id', $articleId, PDO::PARAM_INT);
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? true : false;
    }

    public function delete($id) {
        $stmt = $this->db->prepare(
            'DELETE FROM articles WHERE id = :id'
        );
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // If any rows were affected/deleted, return rue, false otherwise
        return ($stmt->rowCount()) ? true : false;
    }

    public function paginate($page, $perPage, $category = 'all') {
        $offsetAmount = ($page - 1) * $perPage;
        $stmt = $this->db->prepare(
            'SELECT articles.id, articles.title, articles.body, articles.created_at,
				    users.username AS author, article_categories.category_name
			 FROM articles
			 JOIN users ON articles.author_id = users.id
			 JOIN article_categories ON articles.category_id = article_categories.id
			 ORDER BY articles.id DESC
			 LIMIT :perPage
             OFFSET :offset_amount'
         );

         $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
         $stmt->bindParam(':offset_amount', $offsetAmount, PDO::PARAM_INT);
         $stmt->execute();

         $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
         return $articles;
    }

    public function getTotalNumberOfArticles() {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM articles'
        );
        $stmt->execute();

        $num = $stmt->fetch();

        return $num;
    }

}
