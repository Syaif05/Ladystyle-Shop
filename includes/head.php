<?php // includes/head.php ?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                },
                colors: {
                    ls: {
                        50: '#fdf2f8',
                        100: '#fce7f3',
                        200: '#fbcfe8',
                        300: '#f9a8d4',
                        400: '#f472b6',
                        500: '#ec4899', // Primary Pink
                        600: '#db2777',
                        700: '#be185d',
                        800: '#9d174d',
                        900: '#831843',
                    }
                },
                boxShadow: {
                    'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
                    'glow': '0 0 15px rgba(236, 72, 153, 0.3)',
                }
            }
        }
    }
</script>
<style>
    body {
        background-color: #f8fafc;
        background-image: 
            radial-gradient(at 0% 0%, rgba(252, 231, 243, 1) 0, transparent 50%), 
            radial-gradient(at 100% 100%, rgba(224, 231, 255, 1) 0, transparent 50%);
        background-attachment: fixed;
    }
    .glass {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.5);
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
</style>