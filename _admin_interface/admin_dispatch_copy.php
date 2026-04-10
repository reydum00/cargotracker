<?php
session_start();

// If not logged in OR not driver, redirect away
if (!isset($_SESSION['account_id']) || $_SESSION['role'] != 2) {
    header("Location: ../_user_interface/user_signup.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#0047ab] font-serif">

    <div class="flex">
        <nav class="sticky max-lg:hidden top-0 text-[22px] flex flex-col bg-[#f5f5f5] max-w-[360px] h-[960px] pb-40 space-y-10 px-6">
            <div class="logo mb-2">
                <img class="picture mx-auto py-10 w-40" src="images/logo.png">
            </div>
            <div class="side-nav flex flex-col space-y-10 gap-y-4 text-[18px]">
                <button class="hover:bg-gray-400 rounded-md">Dashboard</button>
                <button class="hover:bg-gray-400 rounded-md">Dispatch</button>

                <div>
                    <button onclick="this.nextElementSibling.classList.toggle('hidden')"
                        class="hover:bg-gray-400 rounded-md w-full">
                        Delivery ▼
                    </button>

                    <div class="hidden text-[#000000] mt-2 rounded-md  ">
                        <a class="block hover:bg-gray-400 ml-[60px] py-2 rounded cursor-pointer">Dispatch</a>
                        <a class="block hover:bg-gray-400 ml-[60px] py-2 rounded cursor-pointer">Map</a>
                    </div>
                </div>

                <button class="hover:bg-gray-400 rounded-md">Live Location</button>
                <button class="hover:bg-gray-400 rounded-md">Announcement</button>
                <button class="hover:bg-gray-400 rounded-md">Logs</button>
                <button class="bg-red-700 w-40 mx-auto text-white rounded-md">Log out ⍈</button>
            </div>
        </nav>












    </div>