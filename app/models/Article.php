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
				    articles.img_path, users.id AS authorId, users.username AS author, users.role as authorRole, article_categories.category_name
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
            'SELECT articles.id, articles.title, articles.body, articles.created_at, articles.img_path,
	                article_categories.category_name, users.username as author, users.id as authorId
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
     * Get IDs of all users articles
     *
     * @param  int $userId ID of user, which we get the article IDs from
     *
     * @return array/bool         Array of article IDs, or false
     */
    public function getUserArticleIds($userId)
    {
        $stmt = $this->db->prepare(
            'SELECT articles.id FROM articles WHERE articles.author_id = :userId'
        );
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $articleIds = $stmt->fetchAll(PDO::FETCH_NUM);

        return ($articleIds) ? $articleIds : false;
    }

    /**
     * Update articles author_id
     *
     * @param  int $articleId ID of article to update
     * @param  int $authorId  ID of new author for article
     *
     * @return bool
     */
    public function assignNewAuthorToArticle($articleId, $authorId)
    {
        $stmt = $this->db->prepare('UPDATE articles SET author_id = :authorId WHERE id = :articleId');
        $stmt->bindParam(':authorId', $authorId, PDO::PARAM_INT);
        $stmt->bindparam(':articleId', $articleId, PDO::PARAM_INT);
        $stmt->execute();

        return ($stmt->rowCount()) ? true : false;
    }

    /**
     * Save article to database
     *
     * @param  string $title    Article title
     * @param  string $body     Article body
     * @param  int    $authorId ID of author
     *
     * @return int/bool              Return true, if all fields were filled, false otherwise
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
        $stmt->bindParam(':createdAt', $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $articleCategory, PDO::PARAM_INT);
        $stmt->execute();

        return ($stmt) ? $this->db->lastInsertId() : false;
    }

    /**
     * Save article's image path to article table
     *
     * @param  str    $imgPath    Path to image (/uploads/imgname.extension)
     * @param  int    $articleId  Id of article to save $imgPath to
     *
     * @return bool               True if img path was saved to db, false otherwise
     */
    public function saveArticleImagePath($imgPath, $articleId) {
        $stmt = $this->db->prepare(
            'UPDATE articles
            SET img_path = :imgPath
            WHERE id = :id'
        );
        $stmt->bindParam(':imgPath', $imgPath, PDO::PARAM_STR);
        $stmt->bindParam(':id', $articleId, PDO::PARAM_INT);
        $stmt->execute();

        return ($stmt->rowCount()) ? true : false;
    }

    /**
     * Get list of all categories
     *
     * @return Array Associative Array of cateories
     */
    public function getCategories() {
        $stmt = $this->db->query('SELECT * FROM article_categories');
        $stmt->execute();

        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $categories;
    }

    /**
     * Edit already existing article. Will not update the image here tho.
     * Only the title, body and category
     *
     * @param  int $articleId  Id of article
     * @param  str $title      Articles title
     * @param  str $body       Articles body
     * @param  int $categoryId Id of category
     *
     * @return bool             True if article was updated, false otherwise
     */
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

        return ($stmt->rowCount()) ? true : false;
    }

    /**
     * Deletes article from database
     *
     * @param  int $id Id of article
     *
     * @return bool     True if article was deleted, false otherwise
     */
    public function delete($id) {
        $stmt = $this->db->prepare(
            'DELETE FROM articles WHERE id = :id'
        );
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // If any rows were affected/deleted, return rue, false otherwise
        return ($stmt->rowCount()) ? true : false;
    }

    /**
     * Will return specified number of articles, with a specific offset,
     *  depending on $page and $perPage values.
     *  If category was set, it will limit results to that category,
     *  otherwise, it will take articles from all categories
     *
     * @param  int $page     Which page of articles (this is for offset)
     * @param  int $perPage  How many articles to take
     * @param  int $category If is not -1, it means category was set, so get results from that cat
     *
     * @return Array         Array of articles for specific page
     */
    public function paginate($page, $perPage, $categoryId)
    {
        $offsetAmount = ($page - 1) * $perPage;
        $categoryQuery = ($categoryId >= 1) ? ' WHERE articles.category_id = :categoryId' : '';

        $query = 'SELECT articles.id, articles.title, articles.body, articles.created_at,
                articles.img_path, users.username AS author, article_categories.category_name
                 FROM articles
                 JOIN users ON articles.author_id = users.id
                 JOIN article_categories ON articles.category_id = article_categories.id'
                 . $categoryQuery .
                 ' ORDER BY articles.id DESC
                 LIMIT :perPage
                 OFFSET :offset_amount';

        $stmt = $this->db->prepare($query);

         $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
         $stmt->bindParam(':offset_amount', $offsetAmount, PDO::PARAM_INT);
         ($categoryId >= 1) ? $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT) : '';
         $stmt->execute();

         $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

         return $articles;
    }

    /**
     * Return number of articles in specific category, or total. If category
     * is not passed in, it counts all articles, otherwise, it counts
     * articles from specific category
     *
     * @param  int/bool   $category   id of category, or false
     *
     * @return int                    Number of articles
     */
    public function getTotalNumberOfArticles($categoryId)
    {
        // If category argument was passed, we extend query, to
        // only count articles from that category
        $categoryQuery = ($categoryId >= 1) ? ' WHERE category_id = :categoryId' : '';
        $query = 'SELECT COUNT(*) FROM articles' . $categoryQuery;

        $stmt = $this->db->prepare($query);
        // We check if categoryId is >= to 1, instead of != false, because this way, we
        // validate that its a valid int + that its not false, in simpler manner
        ($categoryId >= 1) ? $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT) : '';
        $stmt->execute();

        $num = $stmt->fetch();

        return $num;
    }

    /**
     * Get id of category, from its name
     *
     * @param  str $category Category name
     *
     * @return int/bool      If category is found, return its id, else return false
     */
    public function getCategoryIdFromName($category) {
        $stmt = $this->db->prepare('SELECT id FROM article_categories WHERE category_name = :category LIMIT 1');
        $stmt->bindParam(':category', $category);
        $stmt->execute();

        $categories = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($stmt) ? $categories['id'] : false;
    }

    /**
     * Search for specific search querry in article title and article body,
     * and return articles matching that query, or false, if none found
     *
     * @param  str $searchTerm What to search for
     *
     * @return Array/bool             Array of found articles, or false
     */
    public function search($searchTerm)
    {
        $stmt = $this->db->prepare(
            "SELECT articles.id, articles.title, articles.body, articles.created_at,
                    articles.img_path, users.username AS author, article_categories.category_name
            FROM articles
            JOIN users ON articles.author_id = users.id
            JOIN article_categories ON articles.category_id = article_categories.id
            WHERE (articles.title LIKE :searchTerm OR articles.body LIKE :searchTerm)
            LIMIT 50"
        );
        $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();

        return ($stmt) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : false;
    }

    /**
     * Get articles author_id, from its ID
     *
     * @param  in $articleId ID of article
     *
     * @return str/bool           Authors ID string, or false
     */
    public function getArticleAuthor($articleId)
    {
        $stmt = $this->db->prepare(
            'SELECT author_id
			FROM articles
			WHERE articles.id = :id
	    ');

        $stmt->bindParam(':id', $articleId, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($data) ? $data['author_id'] : false;
    }

    public function getTotalNumOfArticlesByUser($id)
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(id)
            FROM articles
            WHERE author_id = :authorId'
        );
        $stmt->bindParam(':authorId', $id, PDO::PARAM_INT);
        $stmt->execute();

        return ($stmt) ? $stmt->fetchAll()[0][0] : false;
    }


}
