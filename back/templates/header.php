<?php
    require_once("globals.php");
    require_once("db.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");

    $message = new Message($BASE_URL);

    $flashMessage = $message->getMessage();

    if (!empty($flashMessage["msg"])) {
        // Limpar a mensagem
        $message->clearMessage();
    }

    $userDao = new UserDAO($conn, $BASE_URL);

    $userData = $userDao->verifyToken(false);
     
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieStar</title>
    <link rel="short icon" href="<?php $BASE_URL ?>img/moviestar.ico">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.css" 
    integrity="sha512-lp6wLpq/o3UVdgb9txVgXUTsvs0Fj1YfelAbza2Kl/aQHbNnfTYPMLiQRvy3i+3IigMby34mtcvcrh31U50nRw==" 
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Font Awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" 
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CSS do projeto -->
    <link rel="stylesheet" href="<?php $BASE_URL ?>css/styles.css">
    <link rel="stylesheet" href="<?php $BASE_URL ?>css/auth.css">
    <link rel="stylesheet" href="<?php $BASE_URL ?>css/editprofile.css">
    <link rel="stylesheet" href="<?php $BASE_URL ?>css/newmovie.css">
</head>
<body>
    <header>
        <nav id="main-navbar" class="navbar navbar-expand-lg">
            <a href="<?PHP $BASE_URL ?>" class="navbar-brand">
                <img src="<?PHP $BASE_URL ?>img/logo.svg" alt="" id="logo">
                <span id="moviestar-title">MovieStar</span>
            </a>   
            <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button> 
            <form action="" method="GET" id="search-form" class="form-inline my-2 my-lg-0">
                <input type="search" name="query" id="search" class="form-control mr-sm-2" placeholder="Buscar filmes" aria-label="Search">
                <button class="btn my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
            </form>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav">
                    <?php if($userData): ?>
                        <li class="nav-item">
                            <a href="<?php $BASE_URL ?>newmovie.php" class="nav-link">
                                <i class="far fa-plus-square"></i>Incluir filme
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php $BASE_URL ?>dashboard.php" class="nav-link">Meus Filmes</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php $BASE_URL ?>editprofile.php" class="nav-link bold">
                                <?= $userData->name ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php $BASE_URL ?>logout.php" class="nav-link">Sair</a>
                        </li>
                    <?php else: ?>    
                        <li class="nav-item">
                            <a href="<?php $BASE_URL ?>auth.php" class="nav-link">Entrar / Cadastrar</a>
                        </li>
                    <?php endif; ?>    
                </ul>
            </div>
        </nav>
    </header>
    <?php if(!empty($flashMessage["msg"])): ?>
        <div class="msg-container">
            <p class="msg <?= $flashMessage["type"] ?>"><?= $flashMessage["msg"] ?></p>
        </div> 
    <?php endif; ?>  
     