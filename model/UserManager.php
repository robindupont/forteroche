<?php 
namespace Model;

require_once 'Manager.php';

class UserManager extends Manager
{
	public function login()
	{
		$username = StringManager::normalize((string)$_POST['username']);
		$password = StringManager::normalize((string)$_POST['password']);
		$db = $this->dbConnect();
		$req = $db->prepare('SELECT * FROM users WHERE username = ?');
		$req->execute(array($username));
		$data = $req->fetch();
		if (!empty($data)) {
			if (password_verify($password, $data['password'])) {
				$_SESSION['username'] = $username;
				$_SESSION['surname'] = $data['surname'];
				$_SESSION['name'] = $data['name'];
				$_SESSION['avatar'] = $data['avatar'];
				$_SESSION['role'] = $data['role'];
				return true;
			}
			else {
				throw new \Exception('Mot de passe incorrect.'); 
			}
		}
		else {
			throw new \Exception('Cet utilisateur n\'existe pas.');
		}
	}

	public function getProfile()
	{
		$username = $_SESSION['username'];
		$db = $this->dbConnect();
		$req = $db->prepare('SELECT * FROM users WHERE username = ?');
		$req->execute(array($username));
		$data = $req->fetch();
		if (!empty($data)) {
			$user = new User($data);
			return $user;
		}
		else {
			throw new \Exception('Impossible de récupérer les données utilisateur.');
		}
	}

	public function getAdminInfos()
	{
		$db = $this->dbConnect();
		$req = $db->prepare("SELECT * FROM users WHERE role = 'admin'");
		$req->execute();
		$data = $req->fetch();
		if(!empty($data)) {
			$user = new User($data);
			return $user;
		}
		else {
			throw new \Exception('Impossible de trouver les infos de l\'administrateur.');
		}
	}

	public function editProfile()
	{
		$id = (int)$_POST['id'];
		$username = StringManager::normalize((string)$_POST['username']);
		$surname = trim((string)$_POST['surname']);
		$name = trim((string)$_POST['name']);
		if (!empty($_POST['password'])) {
			$password = StringManager::normalize((string)$_POST['password']);
			$passwordConfirm = StringManager::normalize((string)$_POST['passwordConfirm']);
		}
		$twitter = StringManager::normalize((string)$_POST['twitter']);
		$facebook = StringManager::normalize((string)$_POST['facebook']);
		$instagram = StringManager::normalize((string)$_POST['instagram']);

		$db = $this->dbConnect();
		if (!empty($username) && !empty($surname) && !empty($name)) {
			if (isset($password)) {
				if($password != $passwordConfirm) {
					throw new \Exception('Les mots de passe ne concordent pas.');
				}
				else {
					$password = password_hash($password, PASSWORD_DEFAULT);
					$values = [
						'id' => $id,
						'username' => $username,
						'password' => $password,
						'surname' => $surname,
						'name' => $name,
						'instagram' => $instagram,
						'twitter' => $twitter,
						'facebook' => $facebook
					];
					$req = $db->prepare('UPDATE users SET username=:username, password=:password, surname=:surname, name=:name, instagram=:instagram, twitter=:twitter, facebook=:facebook WHERE id=:id');	
				}
			}
			else {
				$values = [
					'id' => $id,
					'username' => $username,
					'surname' => $surname,
					'name' => $name,
					'instagram' => $instagram,
					'twitter' => $twitter,
					'facebook' => $facebook
				];
				$req = $db->prepare('UPDATE users SET username=:username, surname=:surname, name=:name, instagram=:instagram, twitter=:twitter, facebook=:facebook WHERE id=:id');	
			}
		}
		else {
			throw new \Exception('Au moins un des champs obligatoires est vide.');
		}
		if (!$req->execute($values)) {
			throw new \Exception('Erreur lors de la mise à jour du profil');
		}
		else {
			$_SESSION['username'] = $username;
			$_SESSION['surname'] = $surname;
			$_SESSION['name'] = $name;
		}
	}
}