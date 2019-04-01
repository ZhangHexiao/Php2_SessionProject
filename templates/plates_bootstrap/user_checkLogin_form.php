<!doctype html>
<html lang="en">
<?php if (isset($view['headjs'])): ?>
    <?=$this->section('headjs', $this->fetch('headjs', ['view' => $view]))?>
<?php else: ?>
    <?=$this->section('head', $this->fetch('head', ['view' => $view]))?>
<?php endif ?>

    <style>
        body {
            height: 100%;
        }
        body {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
        }
        .form-signin .checkbox {
            font-weight: 400;
        }
        .form-signin .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 10px;
            font-size: 16px;
        }
        .form-signin .form-control:focus {
            z-index: 2;
        }
        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
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

<body class="text-center">
<form class="form-signin" action= ""  method="post" enctype="multipart/form-data" id="formcheckLogin" >
    <h1 class="h3 mb-3 font-weight-normal"><?php echo isset($this->userMessage) ? $this->getUserMessage() : ''?></h1>
    <label for="inputUsername" class="sr-only">Username</label>
    <input name="username" type="text" id="username" class="form-control" placeholder="Username" required autofocus>
    <label for="inputPassword" class="sr-only">Password</label>
    <input name="password" type="password" id="password" class="form-control" placeholder="Password" required>
    <button name="submit" value="1" class="btn btn-lg btn-primary btn-block" type="submit" form="formcheckLogin">Sign in</button>
    <p><a href="<?php echo $view['urlbaseaddr'] ?>users/index" class="mt-6 inline-block bg-white text-black no-underline px-4 py-3 shadow-lg">Back</a></p>
<!--    <a href="--><?php //echo $view['urlbaseaddr'] ?><!--products/delete/--><?php //echo $product['id'] ?><!--">Delete</a>-->
    <?php if ($view['authenticated'] === 1): ?>
        <div class="alert-success"><p>You log in successfully</p></div>
    <?php endif ?>
    <?php if ($view['authenticated'] === 0): ?>
        <div class="alert-danger"><p>You have not log in, please log in.</p></div>
    <?php endif ?>
    <p class="mt-5 mb-3 text-muted">&copy; 2019</p>
</form>
</body>
</html>