<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Signup</title>

</head>

<body>
    
    <nav class="flex justify-between items-center bg-[#f5f5f5]  h-20 px-6">
        
        <div class="flex h-10">
            <img src="images/logo.png">
        </div>
        
         <div class="NavBar_Div flex space-x-4">
            <a href="index.php"   class="text-[#000000] text-[17px]  no-underline  px-4 py-4">Home</a>
            <a href="about.php"   class="text-[#000000] text-[17px]  no-underline  px-4 py-4">About Us</a>
            <a href="contact.php" class="text-[#000000] text-[17px]  no-underline  px-4 py-4">Contact</a>
            
            <!-- Dropdown div -->
            <div class="relative group">
                <button class="mt-4 text-[17px] hover:text-blue-400">Rates&#9660;</button>
                
                <div class="absolute hidden group-hover:block bg-gray-200 text-[#000000] mt-2 rounded-xl shadow-lg w-40">
                      <a href="rates.php" class="block  px-4 py-2 hover:bg-[#93B8ED] rounded-tl-md rounded-tr-md">Request a Quote</a>
                      <a href="#" class="block  px-4 py-2 hover:bg-[#93B8ED] rounded-bl-md rounded-br-md">Rate Calculator</a>
                </div>  
            </div>
            
            <!-- Dropdown div -->
            <div class="relative group">
                <button  class="mt-4 text-[17px] hover:text-blue-400">Cargo&#9660;</button>

                <div class="absolute hidden group-hover:block bg-gray-200 text-[#000000] mt-2 rounded-md shadow-lg w-40">
                    <a href="tracker.php" class="block  px-4 py-2 hover:bg-[#93B8ED] rounded-tl-md rounded-tr-md">Track your Delivery</a>
                    <a href="#" class="block  px-4 py-2 hover:bg-[#93B8ED] rounded-bl-md rounded-br-md">Contact Courier</a>
                </div>  
            </div>
                
            <a href="trysignup.php"  class="text-[#00   000] text-[17px]  no-underline  px-4 py-4">Sign In</a> 
        </div>
    </nav>








    
    <div>  

        <h2>Signup</h2>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Button</button>
        <p>Already have an account? <a href="signin,php">Sign in</a></p>

    </div>
        
</body>
<footer class="h-[350px] bg-[#000000] text-[#FFFFFF]">
    <p class="float-right w-[180px] py-[40px] pr-[40px]">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi rem sed minima
     similique iusto inventore dignissimos. Ea quod enim, dignissimos ipsum tempora 
     cumque
    </p>
    <p class="float-right w-[180px] py-[40px] pr-[40px]">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi rem sed minima
     similique iusto inventore dignissimos. Ea quod enim, dignissimos ipsum tempora 
     cumque
    </p>
    <p class="float-right w-[180px] py-[40px] pr-[40px]">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi rem sed minima
     similique iusto inventore dignissimos. Ea quod enim, dignissimos ipsum tempora 
     cumque
    </p>
</footer>

</html>