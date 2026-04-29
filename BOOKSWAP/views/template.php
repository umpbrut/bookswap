<?php defined('APP') or die('Accesso Negato') ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISIT BOOKS - BookSwap</title>
    <style>
        body { margin: 0; padding: 0; font-family: sans-serif; overflow-x: hidden; background-color: #fff; }
        .main-container { width: 100%; position: relative; }
        
        header h1 { margin: 15px 0 5px 15px; font-size: 28px; text-decoration: underline; font-family: serif; }
        nav { margin-left: 15px; font-size: 14px; margin-bottom: 20px; }
        nav a { color: blue; text-decoration: underline; }

        .drawing-title {
            text-align: center;
            font-size: 50px;
            font-weight: bold;
            color: #E63946; 
            margin: 20px 0;
            font-family: 'Trebuchet MS', sans-serif;
            letter-spacing: 10px;
        }

        .user-mark {
            position: absolute;
            top: 130px; 
            right: 40px;
            text-align: center;
        }

        /* --- CAROSELLI --- */
        .carousel-wrap {
            width: 100%;
            height: 150px; /* Altezza fissa per il carosello */
            background: #f0f0f0;
            overflow: hidden;
            display: flex;
            align-items: center;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }

        .statue-track {
            display: flex;
            width: max-content;
        }

        .statue-track img {
            height: 300px; /* Altezza doppia perché l'immagine ha due righe */
            display: block;
        }

        /* Posizionamento per vedere solo la riga superiore o inferiore */
        .top-row img { transform: translateY(0px); }    /* Mostra la riga sopra */
        .bottom-row img { transform: translateY(-150px); } /* Mostra la riga sotto */

        @keyframes scrollRight { 0% { transform: translateX(-50%); } 100% { transform: translateX(0); } }
        @keyframes scrollLeft { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }

        .go-right { animation: scrollRight 30s linear infinite; }
        .go-left { animation: scrollLeft 30s linear infinite; }

        section { padding: 20px; }
        footer p { margin-left: 15px; font-size: 14px; }
    </style>
</head>
<body>

    <div class="carousel-wrap">
        <div class="statue-track go-right top-row">
            <img src="autori.jpg" alt="Autori">
            <img src="autori.jpg" alt="Autori">
        </div>
    </div>

    <div class="main-container">
        <header>
            <h1>Gestione Applicativo</h1>
        </header>

        <div class="user-mark">
            <span style="font-size:24px; font-weight:bold;">RO</span><br>
            <span style="font-size:35px;">😊</span>
        </div>

        <main>
            <nav>
                <?php
                    echo "<a href='index.php?page=$this->page&action=index'>ANNUNCI</a> | ";
                    echo "<a href='index.php?page=$this->page&action=create'>CREATE ANNUNCIO</a> | ";
                    echo "<a href='index.php?page=$this->page&action=personal'>MIEI ANNUNCI</a>";
                ?>
            </nav>

            <div class="drawing-title">BOOKSWAP</div>

            <section>
                <?php 
                $action = $_GET['action'] ?? 'index';
                if ($action == 'personal'){
                    include 'table_personal.php';
                } else {
                    include 'table.php';
                }       
                ?>
            </section>
        </main>
    </div>

    <div class="carousel-wrap">
        <div class="statue-track go-left bottom-row">
            <img src="views/img/AUTORI.png" alt="Autori">
            <img src="views/img/AUTORI.png" alt="Autori">
        </div>
    </div>

    <footer>
        <hr>
        <p>ISIT BOOKS - Gestione Applicativo © 2026</p>
    </footer>

</body>
</html>