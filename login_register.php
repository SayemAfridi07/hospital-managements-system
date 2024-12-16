<?php

require('connection.php');
session_start();
# for login
if(isset($_POST['login']))
{
    $email_username = mysqli_real_escape_string($con, $_POST['email_username']);
    
    $query = "SELECT * FROM registered_users WHERE email='$email_username' OR username='$email_username'";
    $result = mysqli_query($con, $query);

    if($result && mysqli_num_rows($result) == 1)
    {
        $result_fetch = mysqli_fetch_assoc($result);
        if($_POST['password'] == $result_fetch['password'])
        {
          $_SESSION['logged_in']=true;
          $_SESSION['username']=$result_fetch['username'];
          header("location: index.php"); 
        }
        else
        {
            echo "
                <script>
                    alert('Incorrect password');
                    window.location.href='index.php';
                </script>
            "; 
        }
    }
    else
    {
        echo "
            <script>
                alert('Email or Username not Registered');
                window.location.href='index.php';
            </script>
        "; 
    }
}


#for registration
if(isset($_POST['register']))
{
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    
    $user_exist_query = "SELECT * FROM registered_users WHERE username = '$username' OR email = '$email'";
    $result = mysqli_query($con, $user_exist_query);

    if($result && mysqli_num_rows($result) > 0)
    {
        $result_fetch = mysqli_fetch_assoc($result);
        if($result_fetch['username'] == $username)
        {
            echo "
                <script>
                    alert('{$result_fetch['username']} - username already taken');
                    window.location.href='index.php';
                </script>
            "; 
        }
        else
        {
            echo "
                <script>
                    alert('{$result_fetch['email']} - Email already taken');
                    window.location.href='index.php';
                </script>
            "; 
        }
    }
    else
    {
        $query = "INSERT INTO registered_users (full_name, username, email, password) VALUES ('$_POST[fullname]', '$username', '$email', '$_POST[password]')";
        if (mysqli_query($con, $query))
        {
            echo "
                <script>
                    alert('Registration Successful');
                    window.location.href='index.php';
                </script>
            ";
        }
        else
        {
            echo "
                <script>
                    alert('Cannot run query');
                    window.location.href='index.php';
                </script>
            ";  
        }
    }
}
?>