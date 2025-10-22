<?php
function obtenerDatosAlbum($pdo, $album_id) {
    // Información del álbum
    $query_album = "
        SELECT a.Titulo
        FROM Albumes a
        WHERE a.IdAlbum = :album_id
    ";
    $stmt_album = $pdo->prepare($query_album);
    $stmt_album->execute([':album_id' => $album_id]);
    $album = $stmt_album->fetch(PDO::FETCH_ASSOC);

    if (!$album) {
        throw new Exception("Álbum no encontrado.");
    }

    // Fotos del álbum (solo título, fichero e id)
    $query_fotos = "
        SELECT f.IdFoto, f.Fichero
        FROM Fotos f
        WHERE f.Album = :album_id
    ";
    $stmt_fotos = $pdo->prepare($query_fotos);
    $stmt_fotos->execute([':album_id' => $album_id]);
    $fotos = $stmt_fotos->fetchAll(PDO::FETCH_ASSOC);

    return [
        'titulo' => $album['Titulo'],
        'fotos' => $fotos,
        'numero_fotos' => count($fotos)
    ];
}
