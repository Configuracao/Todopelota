<?php
// Habilita la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['title']) || !isset($_POST['url']) || !isset($_POST['poster']) || !isset($_POST['year']) || !isset($_POST['description']) || !isset($_POST['background']) || !isset($_POST['movie_id']) || !isset($_POST['genre'])) {
        echo "Por favor, complete todos los campos.";
        exit;
    }

    $title = $_POST['title'];
    $url = $_POST['url'];
    $url2 = $_POST['url2'];
    $url3 = $_POST['url3'];
    $poster = $_POST['poster'];
    $year = $_POST['year'];
    $description = $_POST['description'];
    $background = $_POST['background'];
    $movieId = $_POST['movie_id'];  // Captura el ID de la película
    $genre = $_POST['genre'];  // Captura el género de la película

    $jsonFile = 'movies.json';

    if (file_exists($jsonFile)) {
        $jsonData = json_decode(file_get_contents($jsonFile), true);
        if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
            // Si hay un error al decodificar el JSON, reemplaza con un array vacío
            echo "Error al decodificar el archivo JSON: " . json_last_error_msg() . ". El archivo se regenerará.";
            $jsonData = [];
        }
    } else {
        $jsonData = [];
    }

    // Verifica si el ID de la película ya existe
    $exists = false;
    foreach ($jsonData as $index => $movie) {
        if ($movie['id'] === $movieId) {
            $exists = true;
            break;
        }
    }

    if ($exists) {
        // Reemplaza la película existente conservando el ID
        foreach ($jsonData as $index => $movie) {
            if ($movie['id'] === $movieId) {
                $jsonData[$index] = [
                    "id" => $movieId,  // Conserva el ID de la película
                    "title" => $title,
                    "url" => $url,
                    "url2" => $url2,
                    "url3" => $url3,
                    "poster" => $poster,
                    "year" => $year,
                    "description" => $description,
                    "background" => $background,
                    "genre" => $genre
                ];
                break;
            }
        }
    } else {
        // Agrega la nueva película al principio del array
        array_unshift($jsonData, [
            "id" => $movieId,
            "title" => $title,
            "url" => $url,
            "url2" => $url2,
            "url3" => $url3,
            "poster" => $poster,
            "year" => $year,
            "description" => $description,
            "background" => $background,
            "genre" => $genre
        ]);
    }

    // Guarda el array actualizado en el archivo JSON
    if (file_put_contents($jsonFile, json_encode($jsonData, JSON_PRETTY_PRINT)) === false) {
        echo "Error al escribir en el archivo JSON";
        exit;
    }

    echo "¡Película añadida o reemplazada correctamente!";
}
?>

<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Película</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="generator.css">
    <style>
        #description, #year, #poster, #title, #genre {
            display: none; /* Esta línea oculta visualmente el elemento */
        }
    </style>
</head>
<body>
    <h1>Añadir Película</h1>

    <div class="search-results">
        <input type="text" id="movie-search" placeholder="Buscar">
    </div>
    <div id="toggle-results" onclick="toggleResults()">Mostrar Resultados</div>
    <div id="search-results-container" style="display: none;">
        <ul id="search-results"></ul>
        <p id="no-results-message">Sin resultados, escribe algo en el buscador</p>
    </div>

    <h2>Formulario de Añadir Película</h2>
    <form id="add-movie-form" action="" method="POST">
        <input type="text" id="title" name="title" required>
        <input type="text" id="url" name="url" placeholder="URL Latino">
        <input type="text" id="url2" name="url2" placeholder="URL Español">
        <input type="text" id="url3" name="url3" placeholder="URL subtitulada">
        <input type="url" id="poster" name="poster" required>
        <input type="text" id="year" name="year" required>
        <textarea id="description" name="description" required></textarea>
        <input type="hidden" id="background" name="background">
        <input type="hidden" id="movie-id" name="movie_id">
        <input type="hidden" id="genre" name="genre">

        <input type="submit" value="Añadir Película">
    </form>

    <script>
    $(document).ready(function() {
        let genreMap = {};

        function fetchGenres() {
            $.ajax({
                url: 'https://api.themoviedb.org/3/genre/movie/list',
                data: {
                    api_key: '6f6904c140e654b8086b57ad05687fa8',
                    language: 'es-MX'
                },
                success: function(response) {
                    genreMap = response.genres.reduce((map, genre) => {
                        map[genre.id] = genre.name;
                        return map;
                    }, {});
                }
            });
        }

        fetchGenres();

        $('#movie-search').on('input', function() {
            const query = $(this).val();
            if (query.length > 2) {
                $.ajax({
                    url: 'https://api.themoviedb.org/3/search/movie',
                    data: {
                        api_key: '6f6904c140e654b8086b57ad05687fa8',
                        query: query,
                        language: 'es-MX'
                    },
                    success: function(response) {
                        let movies = response.results;
                        let resultsHtml = '';
                        if (movies.length > 0) {
                            movies.forEach(function(movie) {
                                const background = movie.backdrop_path ? `https://image.tmdb.org/t/p/original${movie.backdrop_path}` : '';
                                const genres = movie.genre_ids.map(id => genreMap[id] || id).join(', ');
                                resultsHtml += `<li data-id="${movie.id}" data-title="${movie.title}" data-poster="https://image.tmdb.org/t/p/w200${movie.poster_path}" data-year="${movie.release_date.split('-')[0]}" data-description="${movie.overview}" data-background="${background}" data-genre="${genres}">
                                    <img src="https://image.tmdb.org/t/p/w200${movie.poster_path}" alt="${movie.title}">
                                    <span>${movie.title} (${movie.release_date.split('-')[0]})</span>
                                </li>`;
                            });
                            $('#no-results-message').hide();
                        } else {
                            $('#no-results-message').show();
                        }
                        $('#search-results').html(resultsHtml);
                        $('#search-results-container').show();
                    }
                });
            } else {
                $('#search-results').empty();
                $('#search-results-container').hide();
            }
        });

        $(document).on('click', '#search-results li', function() {
            const title = $(this).data('title');
            const poster = $(this).data('poster');
            const year = $(this).data('year');
            const description = $(this).data('description');
            const background = $(this).data('background');
            const id = $(this).data('id');
            const genre = $(this).data('genre');

            $('#title').val(title);
            $('#poster').val(poster);
            $('#year').val(year);
            $('#description').val(description);
            $('#background').val(background);
            $('#movie-id').val(id);
            $('#genre').val(genre);

            $('#search-results').empty();
            $('#search-results-container').hide();
        });

        window.toggleResults = function() {
            $('#search-results-container').toggle();
            $('#toggle-results').text($('#search-results-container').is(':visible') ? 'Ocultar Resultados' : 'Mostrar Resultados');
        };

        $('#add-movie-form').on('submit', function(e) {
            e.preventDefault();
            this.submit();
        });
    });
    </script>
</body>
</html>