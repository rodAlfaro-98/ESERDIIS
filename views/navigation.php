<?php
    if(!isset($_SESSION["loggedin"])){
        header("location: ../index.php");
    }
?>

<nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                
                    <div class="dropdown profile-element">        
                        </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a class="dropdown-item" href="profile.html">Profile</a></li>
                            <li><a class="dropdown-item" href="contacts.html">Contacts</a></li>
                            <li><a class="dropdown-item" href="mailbox.html">Mailbox</a></li> -->
                            <li class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="login.html">Logout</a></li>
                        </ul>
                    </div>
                    <div class="logo-element">
                         CV+
                    </div>
                
                <!--<li>
                    <a href="index.html"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboards</span> <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="index.html">Dashboard v.1</a></li>
                        <li><a href="dashboard_2.html">Dashboard v.2</a></li>
                        <li><a href="dashboard_3.html">Dashboard v.3</a></li>
                        <li><a href="dashboard_4_1.html">Dashboard v.4</a></li>
                        <li><a href="dashboard_5.html">Dashboard v.5 </a></li>
                    </ul>
                </li>-->
                <?php
                if($_SESSION["perfil"] == 1){
                    echo "<li>";
                        echo "<a href='menuprincipal.php'><i class='fa fa-pencil-square-o'></i> <span class='nav-label'>Registro</span></a>";
                    echo "</li>";
                    echo "<li>";
                        echo "<a href='./logOut.php'><i class='fa fa-sign-out'></i> <span class='nav-label'>Log Out</span></a>";
                    echo "</li>";
                }else if($_SESSION["perfil"] == 2){
                    echo "<li>";
                        echo "<a href='reportes.php'><i class='fa fa-clipboard'></i> <span class='nav-label'>Reportes</span></a>";
                    echo "</li>";
                    echo "<li>";
                        echo "<a href='./logOut.php'><i class='fa fa-sign-out'></i> <span class='nav-label'>Log Out</span></a>";
                    echo "</li>";
                }else if($_SESSION["perfil"] == 3){
                    echo "<li>";
                        echo "<a href='menuprincipal.php'><i class='fa fa-pencil-square-o'></i> <span class='nav-label'>Registro</span></a>";
                    echo "</li>";
                    echo "<li>";
                        echo "<a href='reportes.php'><i class='fa fa-clipboard'></i> <span class='nav-label'>Reportes</span></a>";
                    echo "</li>";
                    echo "<li>";
                        echo "<a href='./logOut.php'><i class='fa fa-sign-out'></i> <span class='nav-label'>Log Out</span></a>";
                    echo "</li>";
                }
                ?>
            </ul>

        </div>
    </nav>