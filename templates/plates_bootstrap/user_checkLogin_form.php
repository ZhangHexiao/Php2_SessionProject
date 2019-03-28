<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <title>Signin Template · Bootstrap</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.3/examples/sign-in/">

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">


    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
</head>
<body class="text-center">
<form class="form-signin" action= ""  method="post" enctype="multipart/form-data" >
    <h1 class="h3 mb-3 font-weight-normal"><?php echo isset($this->userMessage) ? $this->getUserMessage() : ''?></h1>
    <label for="inputUsername" class="sr-only">Username</label>
    <input name="username" type="text" id="username" class="form-control" placeholder="Username" required autofocus>
    <label for="inputPassword" class="sr-only">Password</label>
    <input name="password" type="password" id="password" class="form-control" placeholder="Password" required>
    <button name="submit" value="1" class="btn btn-lg btn-primary btn-block" type="submit" form="formcheckLogin1">Sign in</button>
<!--    <a href="--><?php //echo $view['urlbaseaddr'] ?><!--products/delete/--><?php //echo $product['id'] ?><!--">Delete</a>-->
    <p class="mt-5 mb-3 text-muted">&copy; 2019</p>
</form>
</body>
</html>