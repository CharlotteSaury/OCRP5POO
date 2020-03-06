<?php

namespace src\model;

use config\Parameter;

class PostManager extends Manager
{
	public function getPostsNb($status = null)
	{
		$sql = 'SELECT COUNT(*) AS postsNb FROM post';

		if ($status != null) {
			$sql .= ' WHERE post.status = :status';
			$req = $this->dbRequest($sql, array($status));
			$req->bindValue(':status', $status, \PDO::PARAM_INT);
			$req->execute();
		
		} else {
			$req = $this->dbRequest($sql);
		}
		
		$postsNb = $req->fetch(\PDO::FETCH_COLUMN);
		return $postsNb;
	}

	public function getPagination($postsPerPage, $postsNb)
	{
		// required page number
		$page_number = ceil((int)$postsNb/$postsPerPage);
 		return $page_number;
	}

	public function getFirstPost($current_page, $postsPerPage)
	{
		$first_post = $current_page*$postsPerPage-$postsPerPage;
		return $first_post;
	}

	public function getPosts($status = null, $first_post = null, $postsPerPage = null, $sortingDate = null)
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

		if ($status != null) {
			$sql .= ' WHERE post.status =' . $status;
		}
		
		if (($first_post != null) || ($postsPerPage != null)) {

			$sql .= ' ORDER BY post.id DESC LIMIT :first_post, :postsPerPage';
			$req = $this->dbRequest($sql, array($first_post, $postsPerPage));
			$req->bindValue(':first_post', $first_post, \PDO::PARAM_INT);
			$req->bindValue(':postsPerPage', $postsPerPage, \PDO::PARAM_INT);
			$req->execute();
		
		} elseif ($sortingDate != null) {

			$sql .= ' ORDER BY post.date_creation ASC';
			$req = $this->dbRequest($sql);
		
		} else {
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

		if ($status == null) {
			$sql .= ' ORDER BY post.date_creation DESC
	        LIMIT 5';
		
		} else {
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

	public function getPostsCategories($postId = null)
	{
		$sql = 'SELECT post_category.post_id AS postId,
			category.name AS name,
			category.id AS id
			FROM post_category
			JOIN category ON category.id = post_category.category_id';

		if ($postId != null) {
			$sql .= ' WHERE post_category.post_id = :postId';
			$req = $this->dbRequest($sql, array($postId));
			$req->bindValue('postId', $postId, \PDO::PARAM_INT);
			$req->execute();
			$categories = $req->fetchAll(\PDO::FETCH_ASSOC);
		
		} else {
			$req = $this->dbRequest($sql);
			$categories = $req->fetchAll(\PDO::FETCH_GROUP | \PDO::FETCH_ASSOC);
		}

		return $categories;
	}

	public function newPostInfos(Parameter $post, $mainImage)
	{
		$sql = 'INSERT INTO post 
			(title, chapo, status, user_id, date_creation, date_update, main_image) 
			VALUES (:title, :chapo, 1, :userId, NOW(), NOW(), :mainImage)';

		$req = $this->dbRequest($sql, array($post->get('title'), $post->get('chapo'), $post->get('userId'), $mainImage));
		$req->bindValue('title', $post->get('title'));
		$req->bindValue('chapo', $post->get('chapo'));
		$req->bindValue('userId', $post->get('userId'), \PDO::PARAM_INT);
		$req->bindValue('mainImage', $mainImage);
		$req->execute();

		$sql = 'SELECT id AS postId FROM post ORDER BY date_creation DESC LIMIT 1';
		$req = $this->dbRequest($sql);
		$postId = $req->fetch(\PDO::FETCH_COLUMN);
		return $postId;
	}

	public function publishPost(Parameter $get)
	{
		if ($get->get('status') == 1) {
			$sql = 'UPDATE post SET status=2 WHERE post.id = :postId';
		
		} else {
			$sql = 'UPDATE post SET status=1 WHERE post.id = :postId';
		}
		
		$req = $this->dbRequest($sql, array($get->get('id')));
		$req->bindValue('postId', $get->get('id'), \PDO::PARAM_INT);
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
		$postCategories = $this->getPostsCategories($postId);

		// Récupération de l'id de la catégorie 

		$sql = 'SELECT category.id AS categoryId FROM category WHERE category.name = :category';
			$req = $this->dbRequest($sql, array($category));
			$req->bindValue('category', $category);
			$req->execute();
			$categoryId = $req->fetch(\PDO::FETCH_COLUMN);
			
		foreach ($postCategories as $postCategory) {
			if ($categoryId == $postCategory['id']) {
				$message = 'Catégorie déjà associée à ce post';
				return $message; 
			}
		}
		
		$sql = 'SELECT COUNT(*) FROM category WHERE category.name = :category';
		$req = $this->dbRequest($sql, array($category));
		$req->bindValue('category', $category);
		$req->execute();
		$exists = $req->fetch(\PDO::FETCH_COLUMN);

		if ($exists != 1) {
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

	public function deleteCategory(Parameter $get)
	{
		$sql = 'DELETE FROM post_category WHERE post_id = :postId';

		if (!$get->get('cat')) {
			$req = $this->dbRequest($sql, array($get->get('id')));
			$req->bindValue('postId', $get->get('id'), \PDO::PARAM_INT);
		
		} else {
			$sql .= ' AND category_id = :categoryId';
			$req = $this->dbRequest($sql, array($get->get('id'), $get->get('cat')));
			$req->bindValue('postId', $get->get('id'), \PDO::PARAM_INT);
			$req->bindValue('categoryId', $get->get('cat'), \PDO::PARAM_INT);
		}
		$req->execute();
	}

	public function editPostInfos(Parameter $post)
	{
		$sql = 'UPDATE post SET post.title = :title, post.chapo = :chapo, ';

		if ($post->get('main_image')) {
			$sql .= ' post.main_image = :main_image, ';
		}
					
		$sql .= 'date_update = NOW() WHERE post.id = :id';

		if ($post->get('main_image')) {
			$req = $this->dbRequest($sql, array($post->get('title'), $post->get('chapo'), $post->get('main_image'), $post->get('postId')));
			$req->bindValue('main_image', $post->get('main_image'));
		
		} else {
			$req = $this->dbRequest($sql, array($post->get('title'), $post->get('chapo'), $post->get('postId')));
		}

		$req->bindValue('title', $post->get('title'));
		$req->bindValue('chapo', $post->get('chapo'));
		$req->bindValue('id', $post->get('postId'), \PDO::PARAM_INT);
		$req->execute();
	}
}

