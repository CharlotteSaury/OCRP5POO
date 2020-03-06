<?php

namespace src\entity;

use src\entity\Entity;

class User extends Entity
{
	private $pseudo;
	private $email;
	private $password;
	private $userRoleId;
	private $role;
	private $registerDate;
	private $firstName;
	private $lastName;
	private $mobile;
	private $website;
	private $home;
	private $birthDate;
	private $avatar;
	private $userAbout;
	private $actCode;
	private $reinitCode;
	private $postsNb;
	private $commentsNb;


  	// SETTERS //

	public function setPseudo($pseudo)
	{
		$this->pseudo = $pseudo;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function setPassword($password)
	{
		$this->password = $password;
	}

	public function setUserRoleId($userRoleId)
	{
		$this->userRoleId = (int) $userRoleId;
	}

	public function setRegisterDate(\DateTime $registerDate)
	{
		$this->registerDate = $registerDate;
	}

	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
	}

	public function setLastName($lastName)
	{
		$this->lastName = $lastName;
	}

	public function setMobile($mobile)
	{
		$this->mobile = $mobile;
	}

	public function setRole($role)
	{
		$this->role = $role;
	}

	public function setWebsite($website)
	{
		$this->website = $website;
	}

	public function setHome($home)
	{
		$this->home = $home;
	}

	public function setBirthDate($birthDate)
	{
		$this->birthDate = $birthDate;
	}

	public function setAvatar($avatar)
	{
		$this->avatar = $avatar;
	}

	public function setUserAbout($userAbout)
	{
		$this->userAbout = $userAbout;
	}

	public function setActCode($actCode)
	{
		$this->actCode = $actCode;
	}

	public function setReinitCode($reinitCode)
	{
		$this->reinitCode = $reinitCode;
	}

	public function setPostNb($postNb)
	{
		$this->postNb = $postNb;
	}

	public function setCommentsNb($commentsNb)
	{
		$this->commentsNb = $commentsNb;
	}



  	// GETTERS //

	public function getPseudo()
	{
		return $this->pseudo;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function getUserRoleId()
	{
		return $this->userRoleId;
	}

	public function getRegisterDate()
	{
		return $this->registerDate;
	}

	public function getFirstName()
	{
		return $this->firstName;
	}

	public function getLastName()
	{
		return $this->lastName;
	}

	public function getMobile()
	{
		return $this->mobile;
	}

	public function getWebsite()
	{
		return $this->website;
	}

	public function getHome()
	{
		return $this->home;
	}

	public function getRole()
	{
		return $this->role;
	}

	public function getBirthDate()
	{
		return $this->birthDate;
	}

	public function getAvatar()
	{
		return $this->avatar;
	}

	public function getUserAbout()
	{
		return $this->userAbout;
	}

	public function getActCode()
	{
		return $this->actCode;
	}

	public function getReinitCode()
	{
		return $this->reinitCode;
	}

	public function getPostsNb()
	{
		return $this->postsNb;
	}

	public function getCommentsNb()
	{
		return $this->commentsNb;
	}
}