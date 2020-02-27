<?php

namespace src\model;

class PostManager extends Manager
{
	
	public function getTotalPostsNb()
	{
		// total post number
		$sql = 'SELECT COUNT(*) AS postsNb FROM post';
		$req = $this->dbRequest($sql);

		$postsNb = $req->fetch(\PDO::FETCH_COLUMN);
		return $postsNb;
	}

	public function getPublishedPostsNb()
	{
		$sql = 'SELECT COUNT(*) AS postsNb FROM post WHERE post.status=2';
		$req = $this->dbRequest($sql);

		$publishedPostNb = $req->fetch(\PDO::FETCH_COLUMN);
		return $publishedPostNb;
	}

	public function getPagination($postsPerPage, $postsNb)
	{
		// required page number
		$page_number = ceil((int)$postsNb/$postsPerPage);

		return $page_number;
	}

	public function getFirstPost($current_page, $postsPerPage)
	{
		/* Billets à récupérer */
		$first_post = $current_page*$postsPerPage-$postsPerPage;
		return $first_post;
	}

	public function getPosts($status, $first_post = null, $postsPerPage = null, $sortingDate = null)
	{
		$sql = 'SELECT post.id AS id, 
			post.title AS title, 
			post.chapo AS chapo, 
			post.status AS status,
			DATE_FORMAT(post.date_creation, \'%d-%m-%Y à %Hh%i \') AS dateCreation,
			DATE_FORMAT(post.date_update, \'%d-%m-%Y à %Hh%i \') AS dateUpdate,
			post.main_image AS mainImage,
			user.pseudo AS pseudo,
			user.avatar AS avatar,
			(SELECT COUNT(*) FROM comment WHERE post.id = comment.post_id AND comment.status = 1) AS approvedCommentsNb,
            (SELECT COUNT(*) FROM comment WHERE post.id = comment.post_id) AS commentsNb
            FROM post 
			JOIN user ON post.user_id = user.id';

		if ($status != null)
		{
			$sql .= ' WHERE post.status =' . $status;
		}
		

		if (($first_post != null) || ($postsPerPage != null))
		{
			$sql .= ' ORDER BY post.id DESC LIMIT :first_post, :postsPerPage';
			$req = $this->dbRequest($sql, array($first_post, $postsPerPage));
			$req->bindValue(':first_post', $first_post, \PDO::PARAM_INT);
			$req->bindValue(':postsPerPage', $postsPerPage, \PDO::PARAM_INT);
			$req->execute();
		}
		elseif ($sortingDate != null)
		{
			$sql .= ' ORDER BY post.date_creation ASC';
			$req = $this->dbRequest($sql);
		}
		else
		{
			$sql .= ' ORDER BY post.id DESC';
			$req = $this->dbRequest($sql);
		}

		$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\src\entity\Post');
		
		$posts = $req->fetchAll();
		return $posts;
	}

	public function getRecentPosts($status = null)
	{
		$sql = 'SELECT post.id AS id, 
			post.title AS title, 
			post.chapo AS chapo, 
			DATE_FORMAT(post.date_creation, \'%d-%m-%Y\') AS dateCreation,
			post.status AS status,
			user.pseudo AS pseudo
			FROM post
			JOIN user ON post.user_id = user.id';

		if ($status == null)
		{
			$sql .= ' ORDER BY post.date_creation DESC
	        LIMIT 5';
		}
		else
		{
			$sql .= ' WHERE post.status=2
	        ORDER BY post.date_creation DESC
	        LIMIT 3';
		}

		$req = $this->dbRequest($sql);
		$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\src\entity\Post');
		
		$posts = $req->fetchAll();
		return $posts;
	}

	public function getPostInfos($postId)
	{
		$sql = 'SELECT post.id AS id,
			post.title AS title, 
			post.chapo AS chapo, 
			post.status AS status,
			post.main_image AS mainImage,
			DATE_FORMAT(post.date_creation, \'%d-%m-%Y à %Hh%i\') AS dateCreation, 
			DATE_FORMAT(post.date_update, \'%d-%m-%Y à %Hh%i\') AS dateUpdate,
			user.pseudo AS pseudo,
			user.avatar AS avatar
	 		FROM post 
	 		JOIN user ON post.user_id=user.id 
	 		WHERE post.id= :id';
		
		$req = $this->dbRequest($sql, array($postId));
		$req->bindValue(':id', $postId, \PDO::PARAM_INT);
		$req->execute();

		$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\src\entity\Post');
		
		$post = $req->fetch();
		return $post;
	}

	public function getAllPostsCategories() {
		
		$sql = 'SELECT post_category.post_id AS postId,
			category.name AS name,
			category.id AS id
			FROM post_category
			JOIN category ON category.id = post_category.category_id';

		$req = $this->dbRequest($sql);

		$allPostsCategories = $req->fetchAll(\PDO::FETCH_GROUP | \PDO::FETCH_ASSOC);

		return $allPostsCategories;
		
	}

	public function getPostCategories($postId) {
		
		$sql = 'SELECT category.name AS name,
			category.id AS id,
			post_category.post_id AS postId
			FROM category 
			JOIN post_category ON category.id = post_category.category_id
			WHERE post_category.post_id = :postId';

		$req = $this->dbRequest($sql, array($postId));
		$req->bindValue('postId', $postId, \PDO::PARAM_INT);
		$req->execute();

		$categories = $req->fetchAll(\PDO::FETCH_ASSOC);
		return $categories;
	}

	public function newPostInfos($title, $chapo, $userId, $mainImage)
	{
		$sql = 'INSERT INTO post 
			(title, chapo, status, user_id, date_creation, date_update, main_image) 
			VALUES (:title, :chapo, 1, :userId, NOW(), NOW(), :mainImage)';

		$req = $this->dbRequest($sql, array($title, $chapo, $userId, $mainImage));
		$req->bindValue('title', $title);
		$req->bindValue('chapo', $chapo);
		$req->bindValue('userId', $userId, \PDO::PARAM_INT);
		$req->bindValue('mainImage', $mainImage);
		$req->execute();

		$sql = 'SELECT id AS postId FROM post ORDER BY date_creation DESC LIMIT 1';
		$req = $this->dbRequest($sql);
		$postId = $req->fetch(\PDO::FETCH_COLUMN);

		return $postId;
	}

	public function publishPost($postId, $status)
	{
		if ($status == 1)
		{
			$sql = 'UPDATE post SET status=2 WHERE post.id = :postId';
		}
		else
		{
			$sql = 'UPDATE post SET status=1 WHERE post.id = :postId';
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

	public function addCategory($postId, $category)
	{
		//Récupération des catégories du post
		$postCategories = $this->getPostCategories($postId);

		// Récupération de l'id de la catégorie 

		$sql = 'SELECT category.id AS categoryId FROM category WHERE category.name = :category';
			$req = $this->dbRequest($sql, array($category));
			$req->bindValue('category', $category);
			$req->execute();
			$categoryId = $req->fetch(\PDO::FETCH_COLUMN);
			
		foreach ($postCategories as $postCategory)
		{
			if ($categoryId == $postCategory['id'])
			{
				$message = 'Catégorie déjà associée à ce post';
				return $message; 
			}
		}
		
		$sql = 'SELECT COUNT(*) FROM category WHERE category.name = :category';
		$req = $this->dbRequest($sql, array($category));
		$req->bindValue('category', $category);
		$req->execute();
		$exists = $req->fetch(\PDO::FETCH_COLUMN);

		if ($exists != 1)
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
			if ($key == 'title' || $key == 'chapo' || $key == 'main_image')
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

