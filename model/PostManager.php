<?php

namespace model;

require_once("model/Manager.php");

class PostManager extends Manager
{
	public function getPagination($postsNb)
	{
		// total post number
		$sql = ('SELECT COUNT(*) AS post_number FROM post WHERE post.status=1');
		$req = $this->dbRequest($sql);

		$total_post_number = $req->fetch();
		$req -> closeCursor();

		// required page number
		$page_number=ceil((int)$total_post_number['post_number']/$postsNb);

		return $page_number;
	}

	public function getFirstPost($current_page, $postsNb)
	{
		/* Billets à récupérer */
		$first_post = $current_page*$postsNb-$postsNb;
		return $first_post;
	}

	public function getPosts($first_post, $postsNb)
	{
		$sql = ('SELECT post.id AS postId, 
			post.title AS title, 
			post.chapo AS chapo, 
			DATE_FORMAT(post.date_creation, \'%d-%m-%Y à %Hh%i \') AS date_creation,
			DATE_FORMAT(post.date_update, \'%d-%m-%Y à %Hh%i \') AS date_update, 
			user.first_name AS first_name, 
			user.last_name AS last_name, 
			content.content AS mainImg_url, 
			user.avatar AS avatar
			FROM post 
			JOIN user ON post.user_id=user.id   
			JOIN content ON post.id = content.post_id 
			JOIN content_type ON content_type.id=content.content_type_id 
			WHERE post.status=1 AND content_type.id=1
			ORDER BY postID DESC
			LIMIT :first_post, :postsNb');

		$req = $this->dbRequest($sql, array($first_post, $postsNb));
		$req->bindValue(':first_post', $first_post, \PDO::PARAM_INT);
		$req->bindValue(':postsNb', $postsNb, \PDO::PARAM_INT);
		$req->execute();

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
		$sql = ('SELECT post.title AS title, 
			post.chapo AS chapo, 
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
		$sql = ('SELECT content.content AS content, 
			content_type.id AS content_type 
			FROM post 
			JOIN content ON post.id = content.post_id 
			JOIN content_type ON content_type.id=content.content_type_id 
			WHERE post.id= :id 
			ORDER BY content.id ASC');
		
		$req = $this->dbRequest($sql, array($postId));
		$req->bindValue(':id', $postId, \PDO::PARAM_INT);
		$req->execute();
		return $req;
	}

	public function getPostCategories($postId) {
		
		$sql = ('SELECT category.name AS categoryName
			FROM category 
			JOIN post_category ON category.id = post_category.category_id
			JOIN post ON post.id = post_category.post_id
			WHERE post.id=:id');

		$req = $this->dbRequest($sql, array($postId));
		$req->bindValue(':id', $postId, \PDO::PARAM_INT);
		$req->execute();
		return $req;
	}

	public function getRecentPosts()
	{
		$sql = ('SELECT post.id AS postId, 
			post.title AS title, 
			post.chapo AS chapo, 
			DATE_FORMAT(post.date_creation, \'%d-%m-%Y\') AS date_creation 
			FROM post
			WHERE post.status=1
	        ORDER BY post.date_creation DESC
	        LIMIT 3');

		$req = $this->dbRequest($sql);
		return $req;
	}

}

