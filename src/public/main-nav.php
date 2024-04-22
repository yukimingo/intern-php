<div class="navbar navbar-expand navbar-dark" style="margin-bottom: 80px;">
    <div class="container">
        <a href="index.php" class="navbar-brand title"><h1 class="h4 mb-0">24-Intern</h1></a>
        <ul class="navbar-nav">
            <?php
            if(isset($_SESSION["id"])){    
            ?>
            <li class="nav-item">
                <a href="index.php" class="nav-link">実行ページ</a>
            </li>
            <li class="nav-item">
                <a href="log.php" class="nav-link">実行履歴</a>
            </li>
                <?php
                if($_SESSION["roll"] === 1){    
                ?>
                <li class="nav-item">
                    <a href="category.php" class="nav-link">カテゴリー</a>
                </li>
                <?php
                }    
                ?>
            <li class="nav-item">
                    <a href="" class="nav-link"><?= $_SESSION["name"]?></a>
                </li>   
            <li class="nav-item">
                <a href="./actions/logout.php" class="nav-link">ログアウト</a>
            </li>
            <?php
            }else{ 
            ?>
            <li class="nav-item">
                <a href="sign-up.php" class="nav-link">新規登録</a>
            </li>
            <li class="nav-item">
                <a href="login.php" class="nav-link">ログイン</a>
            </li>
            <?php
            }    
            ?>
        </ul>
    </div>
</div>