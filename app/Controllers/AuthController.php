<?php
namespace Controllers;
use Core\Controller;
use Models\User;

final class AuthController extends Controller {

    public function login(): void {
        if (!empty($_SESSION['uid'])) { $this->redirect('/dashboard'); }
        $this->render('login', [
            'title' => 'Connexion',
            'csrf'  => $this->csrfToken(),
            'message' => $_SESSION['flash'] ?? '',
        ]);
        unset($_SESSION['flash']);
    }
  
    public function doLogin(): void {
        if (!$this->checkCsrf($_POST['csrf'] ?? null)) { http_response_code(400); exit('CSRF'); }

        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            $_SESSION['flash'] = 'Identifiants requis';
            $this->redirect('/');
        }

        $user = User::findByUsername($username);
        if (!$user || !password_verify($password, $user['mdp'])) {
            $_SESSION['flash'] = 'Mauvais identifiant ou mot de passe';
            $this->redirect('/');
        }

        $_SESSION['uid'] = (int)$user['id'];
        $_SESSION['name'] = $user['login'];
        $this->redirect('/dashboard');
    }

    public function dashboard(): void {
        if (empty($_SESSION['uid'])) $this->redirect('/');
        $this->render('dashboard', ['title'=>'Dashboard', 'username'=>$_SESSION['name'] ?? 'Utilisateur']);
    }

    public function logout(): void {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time()-42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        $this->redirect('/');
    }
   
    

}
