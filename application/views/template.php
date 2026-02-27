<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? $title : 'M-Sales Application'; ?></title>
    <!-- Global Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/@fontsource-variable/tiktok-sans@latest/index.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['TikTok Sans Variable', 'Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Shared Style -->
    <style>
        body { font-family: 'TikTok Sans Variable', 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <?php echo isset($contents) ? $contents : ''; ?>
</body>
</html>
