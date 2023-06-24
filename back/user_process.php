<?php
    require_once("globals.php");
    require_once("db.php");
    require_once("models/User.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");

    $message = new Message($BASE_URL);

    $userDao = new UserDAO($conn, $BASE_URL);

    // Resgata o tipo do formulário
    $type = filter_input(INPUT_POST, "type");

    // Atualizar usuário
    if ($type === "update") {

        // Resgata dados do usuário
        $userData = $userDao->verifyToken();

        // Receber dados do post
        $name = filter_input(INPUT_POST, "name");
        $lastname = filter_input(INPUT_POST, "lastname");
        $email = filter_input(INPUT_POST, "email");
        $bio = filter_input(INPUT_POST, "bio");

        // Criar um novo objeto de usuário
        $user = new User();

        // Preencher os dados do usuário
        $userData->name = $name;
        $userData->lastname = $lastname;
        $userData->email = $email;
        $userData->bio = $bio;

        // Upload da imagem
        if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
            
            $image = $_FILES["image"];
            $imageTypes = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
            $jpgArray = ["image/jpeg", "image/jpg"];
            $pngArray = ["image/png"];

            // Checagem do tipo de imagem
            if (in_array($image["type"], $imageTypes)) {

                // Checagem se jpg
                if (in_array($image["type"], $jpgArray)) {
                    
                    $imageFile = imagecreatefromjpeg($image["tmp_name"]);

                // Imagem é png    
                } elseif (in_array($image["type"], $pngArray)) {
                    
                    $imageFile = imagecreatefrompng($image["tmp_name"]);
                    
                } else {

                    $imageFile = imagecreatefromwebp($image["tmp_name"]);

                }

                // Gera o nome da imagem
                $imageName = $user->imageGenerateName();

                imagejpeg($imageFile, "./img/users/" . $imageName, 100);

                $userData->image = $imageName;

            } else {
                
                // Redireciona caso imagem seja em formato não permitido
                $message->setMessage("Tipo de imagem inválido!", "error", "back");
            }
            
        }

        $userDao->update($userData);
    
    // Atualizar senha do usuário    
    } else if ($type === "changepassword") {

        // Receber dados do post
        $password = filter_input(INPUT_POST, "password");
        $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

        // Resgata dados do usuário
        $userData = $userDao->verifyToken();

        $id = $userData->id;
        
        if ($password == $confirmpassword) {
            
            // Criar um novo objeto de usuário
            $user = new User();

            $finalPassword = $user->generatePassword($password);

            $user->password = $finalPassword;
            $user->id = $id;

            $userDao->changePassword($user);

        } else {
            
            // Retorna mensagem de erro caso senhas forem diferentes
            $message->setMessage("As senhas não são iguais!", "error", "back");
        }
        
    } else {

        $message->setMessage("Informações inválidas!", "error", "index.php");
    }
