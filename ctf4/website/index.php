<?php
session_start();

?>
<html>

<head>
    <title>DarkHole V2</title>
    <link rel="stylesheet" href="style/index.css">
</head>
<body>
<div class="wrapper">
    <div class="Container">
        <div class="nav">
            <div class="logo">
                DarkHole V2
            </div>
            <div class="menu">
                <ul class="navMenu">
                    <?php if(isset($_SESSION['userid'])){
                        ?>
                        <li><a href="logout.php">logout</a></li>
                        <li><a href="dashboard.php?id=<?php echo $_SESSION['userid']; ?>">Dashboard</a> </li>
                        <?php
                    }else{
                        ?>
                        <li><a href="login.php">Login</a></li>
                        <?php
                    } ?>

                </ul>
            </div>
        </div>
        <div class="header">
            <h1>The Spark Diamond</h1>
            <p>New area / Future City</p>
            <button type="button">View Details</button>
        </div>
    </div>
</div>
</body>
</html>