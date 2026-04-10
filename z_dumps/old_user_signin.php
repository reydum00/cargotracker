<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Signup</title>

    <style>

        body {
           
            background-color: #152238;
        }

      

        form  {
            width: 60%; 
            height: 80%; 
            margin: 100px auto; 
            padding: 20px; 
            border-color: black;
            border-style: solid;
            border-radius: 30px 30px 30px 30px;
        }

         input {
            width: 40%;
            padding: 12px;
            margin: 20px auto;
            display: block;
            background-color: black;
            border-radius: 8px;
           
        }

        button{
            width: 30px; 
            padding: 10%; 
            background-color: #b44d4dff; 
            color:#b44d4dff; 
            border: black;
        }
        
        input::placeholder,
        input::-webkit-input-placeholder,
        input::-moz-placeholder,
        input:-ms-input-placeholder {
            color: red;
            opacity: 1;
        }


    </style>

</head>




<body>
    <nav class="flex justify-between items-center bg-black h-20 px-6">
        <a class="text-[#1C64FF] text-lg font-semibold px-4 py-[14px]" href="index.php">Logo</a>
        
        <div class="NavBar_Div flex space-x-4">
            <a href="index.php"   class="text-[#FFFFFF] text-[17px]  px-4 py-2">Home</a>
            <a href="about.php"   class="text-[#FFFFFF] text-[17px]  no-underline  px-4 py-2">About Us</a>
            <a href="contact.php" class="text-[#FFFFFF] text-[17px]  no-underline  px-4 py-2">Contact</a>
            <a href="rates.php"   class="text-[#FFFFFF] text-[17px]  no-underline  px-4 py-2">Rates</a>
            <a href="tracker.php" class="text-[#FFFFFF] text-[17px]  no-underline  px-4 py-2">Cargo</a>
            <a href="signin.php"  class="text-[#FFFFFF] text-[17px]  no-underline no-underline bg-[#004F94]/30 text-white rounded-md   px-1 py-2">Sign Up/Sign In</a> 
        </div>
    </nav>



    
    <div>  
        <form>
            <h2>Signup</h2>
            <input class="placeholder-white focus:placeholder-transparent" type="email" name="email" placeholder="Email" required >
            <input class="placeholder-white focus:placeholder-transparent" type="text" name="username" placeholder="Username" required>
            <input class="placeholder-white focus:placeholder-transparent" type="password" name="password" placeholder="Password" required>
            <button  type="submit">Button</button>
            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
        </form>
    </div>
        
</body>

</html>