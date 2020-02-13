<?php

namespace model;

require_once("model/Manager.php");

class PostManager extends Manager
{
	
	public function getTotalPostsNb()
	{
		// total post number
		$sql = ('SELECT COUNT(*) AS postsNb FROM post');
		$req = $this->dbRequest($sql);

		$totalPostNb = $req->fetch();
		$postsNb = $totalPostNb['postsNb'];
		return $postsNb;
	}

	public function getPublishedPostsNb()
	{
		$sql = ('SELECT COUNT(*) AS postsNb FROM post WHERE post.status=1');
		$req = $this->dbRequest($sql);

		$publishedPostNb = $req->fetch();
		$postsNb = $publishedPostNb['postsNb'];
		return $postsNb;
	}

	public function getPagination($postsPerPage, $postsNb)
	{
		// required page number
		$page_number=ceil((int)$postsNb/$postsPerPage);

		return $page_number;
	}

	public function getFirstPost($current_page, $postsPerPage)
	{
		/* Billets à récupérer */
		$first_post = $current_page*$postsPerPage-$postsPerPage;
		return $first_post;
	}

	public function getPosts($first_post = null, $postsPerPage = null, $nbComments = null)
	{
		$sql = 'SELECT post.id AS postId, 
			post.title AS title, 
			post.chapo AS chapo, 
			post.status AS status,
			DATE_FORMAT(post.date_creation, \'%d-%m-%Y à %Hh%i \') AS date_creation,
			DATE_FORMAT(post.date_update, \'%d-%m-%Y à %Hh%i \') AS date_update,
			post.main_image AS main_image, 
			user.first_name AS first_name, 
			user.last_name AS last_name, 
			user.avatar AS avatar';

			if ($nbComments != null)
			{
				$sql .= ', (SELECT COUNT(*) FROM comment WHERE post.id = comment.post_id AND comment.status = 1) AS approvedCommentsNb,
            (SELECT COUNT(*) FROM comment WHERE post.id = comment.post_id) AS commentsNb';
			}

			$sql .= ' FROM post 
					JOIN user ON post.user_id=user.id';

		if (($first_post != null) || ($postsPerPage != null))
		{
			$sql .= ' AND post.status=1 ORDER BY postID DESC LIMIT :first_post, :postsPerPage';
			$req = $this->dbRequest($sql, array($first_post, $postsPerPage));
			$req->bindValue(':first_post', $first_post, \PDO::PARAM_INT);
			$req->bindValue(':postsPerPage', $postsPerPage, \PDO::PARAM_INT);
			$req->execute();
		}
		else
		{
			$sql .= ' ORDER BY postID DESC';
			$req = $this->dbRequest($sql);
		}
		
		return $req;
	}

	public function getCategories()
	{
		$sql = ('SELECT category.id AS category_id,
			category.name AS category_name
			FROM category
			ORDER BY category_name');

		$req = $this->dbRequest($sql);
		return $req;
	}

	public function getPostInfos($postId)
	{
		$sql = ('SELECT post.id AS postId,
			post.title AS title, 
			post.chapo AS chapo, 
			post.status AS status,
			post.main_image AS main_image,
			DATE_FORMAT(post.date_creation, \'%d-%m-%Y à %Hh%i\') AS date_creation, 
			DATE_FORMAT(post.date_update, \'%d-%m-%Y à %Hh%i\') AS date_update,
			user.first_name AS first_name, 
			user.last_name AS last_name, 
			user.avatar AS avatar
	 		FROM post 
	 		JOIN user ON post.user_id=user.id 
	 		WHERE post.id= :id');
		
		$req = $this->dbRequest($sql, array($postId));
		$req->bindValue(':id', $postId, \PDO::PARAM_INT);
		$req->execute();
		return $req;
	}

	public function getPostContents($postId)
	{
		$sql = 'SELECT post.id AS postId,
			content.content AS content,
			content.id AS contentId, 
			content_type.id AS content_type 
			FROM post 
			JOIN content ON post.id = content.post_id 
			JOIN content_type ON content_type.id=content.content_type_id 
			WHERE post.id= :id 
			ORDER BY content.id ASC';
		
		$req = $this->dbRequest($sql, array($postId));
		$req->bindValue(':id', $postId, \PDO::PARAM_INT);
		$req->execute();
		return $req;
	}

	public function getAllPostsCategories() {
		
		$sql = 'SELECT post.id AS postId,
			category.name AS categoryName
			FROM post 
			JOIN post_category ON post.id = post_category.post_id
			JOIN category ON category.id = post_category.category_id';

		$req = $this->dbRequest($sql);

		$allPostsCategories = $req->fetchAll();

		return $allPostsCategories;
		
	}

	public function getPostCategories($postId) {
		
		$sql = ('SELECT category.name AS categoryName,
			category.id AS categoryId,
			post.id AS postId
			FROM category 
			JOIN post_category ON category.id = post_category.category_id
			JOIN post ON post.id = post_category.post_id
			WHERE post.id=:id');

		$req = $this->dbRequest($sql, array($postId));
		$req->bindValue(':id', $postId, \PDO::PARAM_INT);
		$req->execute();
		return $req;
	}

	public function getRecentPosts($status = null)
	{
		$sql = 'SELECT post.id AS postId, 
			post.title AS title, 
			post.chapo AS chapo, 
			DATE_FORMAT(post.date_creation, \'%d-%m-%Y\') AS date_creation,
			post.status AS status,
			user.first_name AS first_name,
			user.last_name AS last_name
			FROM post
			JOIN user ON post.user_id = user.id';

		if ($status == null)
		{
			$sql .= ' ORDER BY post.date_creation DESC
	        LIMIT 5';
		}
		else
		{
			$sql .= ' WHERE post.status=1
	        ORDER BY post.date_creation DESC
	        LIMIT 3';
		}
		

		$req = $this->dbRequest($sql);
		return $req;
	}

	public function newPostInfos($title, $chapo, $userId, $mainImage)
	{
		$sql = 'INSERT INTO post 
			(title, chapo, status, user_id, date_creation, date_update, main_image) 
			VALUES (:title, :chapo, 0, :userId, NOW(), NOW(), :mainImage)';

		$req = $this->dbRequest($sql, array($title, $chapo, $userId, $mainImage));
		$req->bindValue('title', $title);
		$req->bindValue('chapo', $chapo);
		$req->bindValue('userId', $userId, \PDO::PARAM_INT);
		$req->bindValue('mainImage', $mainImage);
		$req->execute();

		$sql = 'SELECT id AS postId FROM post ORDER BY date_creation DESC LIMIT 1';
		$req = $this->dbRequest($sql);
		$postId = $req->fetch(\PDO::FETCH_ASSOC);

		return $postId['postId'];
	}

	public function publishPost($postId, $status)
	{
		if ($status == 0)
		{
			$sql = 'UPDATE post SET status=1 WHERE post.id = :postId';
		}
		else
		{
			$sql = 'UPDATE post SET status=0 WHERE post.id = :postId';
		}
		
		$req = $this->dbRequest($sql, array($postId));
		$req->bindValue('postId', $postId, \PDO::PARAM_INT);
		$req->execute();
	}

	public function deletePost($postId)
	{
		$sql = 'DELETE FROM post WHERE post.id = :postId';

		$req = $this->dbRequest($sql, array($postId));
		$req->bindValue('postId', $postId, \PDO::PARAM_INT);
		$req->execute();
	}

	public function dateUpdate($postId)
	{
		$sql = 'UPDATE post SET post.date_update = NOW() WHERE post.id = :postId';

		$req = $this->dbRequest($sql, array($postId));
		$req->bindValue('postId', $postId, \PDO::PARAM_INT);
		$req->execute();
	}

	public function updateMainPostPicture($postId, $url)
	{
		$sql = 'UPDATE post SET post.main_image = :url WHERE post.id = :postId';

		$req = $this->dbRequest($sql, array($url, $postId));
		$req->bindValue('url', $url);
		$req->bindValue('postId', $postId, \PDO::PARAM_INT);
		$req->execute();
	}

	public function deleteMainPostPicture($postId)
	{
		$sql = 'UPDATE post SET post.main_image = NULL WHERE post.id = :postId';

		$req = $this->dbRequest($sql, array($postId));
		$req->bindValue('postId', $postId, \PDO::PARAM_INT);
		$req->execute();
	}

	public function updatePostPicture($contentId, $url)
	{
		$sql = 'UPDATE content SET content.content = :url WHERE content.id = :contentId';

		$req = $this->dbRequest($sql, array($url, $contentId));
		$req->bindValue('url', $url);
		$req->bindValue('contentId', $contentId, \PDO::PARAM_INT);
		$req->execute();
	}

	public function deleteContent($contentId)
	{
		$sql = 'DELETE FROM content WHERE content.id = :contentId';

		$req = $this->dbRequest($sql, array($contentId));
		$req->bindValue('contentId', $contentId, \PDO::PARAM_INT);
		$req->execute();
	}

	public function addParagraph($postId)
	{
		$sql = 'INSERT INTO content (post_id, content_type_id, content) 
				VALUES (:postId, 2, "")';

		$req = $this->dbRequest($sql, array($postId));
		$req->bindValue('postId', $postId, \PDO::PARAM_INT);
		$req->execute();
	}

	public function editParagraph($newParagraphs)
	{
		foreach ($newParagraphs AS $key => $value)
		{
			$sql = 'UPDATE content SET content.content = :content 
				WHERE content.id = :contentId';

			$req = $this->dbRequest($sql, array($value, $key));
			$req->bindValue('content', $value);
			$req->bindValue('contentId', $key, \PDO::PARAM_INT);
			$req->execute();
		}
	}

	public function addPicture($postId, $content)
	{
		$sql = 'INSERT INTO content (post_id, content_type_id, content) 
				VALUES (:postId, 1, :content)';

		$req = $this->dbRequest($sql, array($postId, $content));
		$req->bindValue('postId', $postId, \PDO::PARAM_INT);
		$req->bindValue('content', $content);
		$req->execute();
	}

	public function addCategory($postId, $category)
	{
		//Récupération des catégories du post
		$postCategories = $this->getPostCategories($postId);
		$postCategoriesTable = [];
		
		while ($donnees = $postCategories->fetch(\PDO::FETCH_ASSOC))
		{
			array_push($postCategoriesTable, $donnees['categoryId']);
		}

		// Récupération de l'id de la catégorie 

		$sql = 'SELECT category.id AS categoryId FROM category WHERE category.name = :category';
			$req = $this->dbRequest($sql, array($category));
			$req->bindValue('category', $category);
			$req->execute();
			$donnees = $req->fetch(\PDO::FETCH_ASSOC);
			

		if (in_array($donnees['categoryId'], $postCategoriesTable)) // catégorie déjà associée à ce post
		{
			$message = 'Catégorie déjà associée à ce post';
			return $message; 
		}

		else
		{
			$sql = 'SELECT COUNT(*) AS categoryExists FROM category WHERE category.name = :category';
			$req = $this->dbRequest($sql, array($category));
			$req->bindValue('category', $category);
			$req->execute();
			$donnees = $req->fetch(\PDO::FETCH_ASSOC);

			if ($donnees['categoryExists'] != 1)
			{
				$sql = 'INSERT INTO category (name) VALUES (:category)';
				$req = $this->dbRequest($sql, array($category));
				$req->bindValue('category', $category);
				$req->execute();
			}

			$sql = 'INSERT INTO post_category (post_id, category_id) 
			VALUES (:postId, 
			(SELECT category.id FROM category WHERE category.name = :category))';

			$req = $this->dbRequest($sql, array($postId, $category));
			$req->bindValue('postId', $postId, \PDO::PARAM_INT);
			$req->bindValue('category', $category);
			$req->execute();

			$message = 'Catégorie ajoutée';
			return $message; 
		}

		
	}

	public function deleteCategory($postId, $categoryId)
	{
		$sql = 'DELETE FROM post_category WHERE post_id = :postId AND category_id = :categoryId';
				
		$req = $this->dbRequest($sql, array($postId, $categoryId));
		$req->bindValue('postId', $postId, \PDO::PARAM_INT);
		$req->bindValue('categoryId', $categoryId, \PDO::PARAM_INT);
		$req->execute();
	}

	public function editPostInfos($newPostInfos, $postId)
	{
		$sql = 'UPDATE post SET';

		foreach ($newPostInfos AS $key => $value)
		{
			if ($key == 'title' || $key == 'chapo')
			{
				$sql .= ' ' . $key . '="' . $value . '", ';
			}
		}
					
		$sql .= 'date_update = NOW() WHERE post.id = :id';
		$req = $this->dbRequest($sql, array($postId));
		$req->bindValue('id', $postId, \PDO::PARAM_INT);
		$req->execute();
	}

}

