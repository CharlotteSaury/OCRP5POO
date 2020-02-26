<?php

namespace entity;

class User extends Entity
{
	protected $pseudo,
	$email,
	$password,
	$userRoleId,
	$role,
	$registerDate,
	$firstName,
	$lastName,
	$mobile,
	$website,
	$home,
	$birthDate,
	$avatar,
	$userAbout,
	$actCode,
	$reinitCode,
	$postsNb,
	$commentsNb;


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

	public function pseudo()
	{
		return $this->pseudo;
	}

	public function email()
	{
		return $this->email;
	}

	public function password()
	{
		return $this->password;
	}

	public function userRoleId()
	{
		return $this->userRoleId;
	}

	public function registerDate()
	{
		return $this->registerDate;
	}

	public function firstName()
	{
		return $this->firstName;
	}

	public function lastName()
	{
		return $this->lastName;
	}

	public function mobile()
	{
		return $this->mobile;
	}

	public function website()
	{
		return $this->website;
	}

	public function home()
	{
		return $this->home;
	}

	public function role()
	{
		return $this->role;
	}

	public function birthDate()
	{
		return $this->birthDate;
	}

	public function avatar()
	{
		return $this->avatar;
	}

	public function userAbout()
	{
		return $this->userAbout;
	}

	public function actCode()
	{
		return $this->actCode;
	}

	public function reinitCode()
	{
		return $this->reinitCode;
	}

	public function postsNb()
	{
		return $this->postsNb;
	}

	public function commentsNb()
	{
		return $this->commentsNb;
	}
}