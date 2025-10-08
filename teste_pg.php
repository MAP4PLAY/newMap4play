<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste PostgreSQL - MAP 4 PLAY</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .success { color: green; }
        .error { color: red; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #4CAF50;
            color: white;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success { background: #4CAF50; color: white; }
        .badge-danger { background: #f44336; color: white; }
    </style>
</head>
<body>
    <h1>🧪 Teste de Conexão PostgreSQL + PostGIS</h1>

    <?php
    include 'conexao_pg.php';

    echo '<div class="box">';
    echo '<h2>1️ Teste de Conexão</h2>';
    if ($conn) {
        echo '<p class="success">Conexão estabelecida com sucesso!</p>';
        
        $version = pg_version($conn);
        echo '<p><strong>Versão do PostgreSQL:</strong> ' . $version['server'] . '</p>';
    } else {
        echo '<p class="error">Falha na conexão!</p>';
    }
    echo '</div>';

    echo '<div class="box">';
    echo '<h2>2️ Verificar PostGIS</h2>';
    $result = pg_query($conn, "SELECT PostGIS_version();");
    if ($result && pg_num_rows($result) > 0) {
        $row = pg_fetch_row($result);
        echo '<p class="success">PostGIS está instalado!</p>';
        echo '<p><strong>Versão:</strong> ' . $row[0] . '</p>';
    } else {
        echo '<p class="error">PostGIS não encontrado!</p>';
    }
    echo '</div>';

    echo '<div class="box">';
    echo '<h2>3️ Tabelas Criadas</h2>';
    $sql = "SELECT table_name FROM information_schema.tables 
            WHERE table_schema = 'public' 
            ORDER BY table_name";
    $result = pg_query($conn, $sql);
    
    if ($result) {
        echo '<ul>';
        while ($row = pg_fetch_assoc($result)) {
            echo '<li>' . $row['table_name'] . '</li>';
        }
        echo '</ul>';
    }
    echo '</div>';

    echo '<div class="box">';
    echo '<h2>4️ Quantidade de Registros</h2>';
    
    $tabelas = ['quadras', 'comentarios', 'contatos'];
    echo '<table>';
    echo '<tr><th>Tabela</th><th>Quantidade</th></tr>';
    
    foreach ($tabelas as $tabela) {
        $result = pg_query($conn, "SELECT COUNT(*) as total FROM $tabela");
        if ($result) {
            $row = pg_fetch_assoc($result);
            echo '<tr><td>' . $tabela . '</td><td>' . $row['total'] . '</td></tr>';
        }
    }
    echo '</table>';
    echo '</div>';

    echo '<div class="box">';
    echo '<h2>5️ Quadras Cadastradas</h2>';
    $sql = "SELECT 
                nome_quadra, 
                zona, 
                tipo_esporte, 
                acessivel,
                ST_Y(localizacao::geometry) as lat,
                ST_X(localizacao::geometry) as lng
            FROM quadras 
            ORDER BY nome_quadra";
    
    $result = pg_query($conn, $sql);
    
    if ($result && pg_num_rows($result) > 0) {
        echo '<table>';
        echo '<tr><th>Nome</th><th>Zona</th><th>Esporte</th><th>Acessível</th><th>Coordenadas</th></tr>';
        
        while ($row = pg_fetch_assoc($result)) {
            $acessivel = ($row['acessivel'] === 't') ? 
                '<span class="badge badge-success">Sim</span>' : 
                '<span class="badge badge-danger">Não</span>';
            
            echo '<tr>';
            echo '<td>' . $row['nome_quadra'] . '</td>';
            echo '<td>' . $row['zona'] . '</td>';
            echo '<td>' . $row['tipo_esporte'] . '</td>';
            echo '<td>' . $acessivel . '</td>';
            echo '<td>' . round($row['lat'], 4) . ', ' . round($row['lng'], 4) . '</td>';
            echo '</tr>';
        }
        
        echo '</table>';
    } else {
        echo '<p class="error">Nenhuma quadra cadastrada ainda.</p>';
    }
    echo '</div>';
    echo '<div class="box">';
    echo '<h2>6️ Teste de Busca por Proximidade</h2>';
    echo '<p>Buscando quadras num raio de 10km do centro de São Paulo (lat: -23.5505, lng: -46.6333)</p>';
    
    $sql = "SELECT 
                nome_quadra,
                zona,
                ROUND(
                    ST_Distance(
                        localizacao,
                        ST_SetSRID(ST_MakePoint(-46.6333, -23.5505), 4326)::geography
                    )::numeric / 1000, 2
                ) as distancia_km
            FROM quadras
            WHERE ST_DWithin(
                localizacao,
                ST_SetSRID(ST_MakePoint(-46.6333, -23.5505), 4326)::geography,
                10000
            )
            ORDER BY distancia_km";
    
    $result = pg_query($conn, $sql);
    
    if ($result && pg_num_rows($result) > 0) {
        echo '<table>';
        echo '<tr><th>Nome</th><th>Zona</th><th>Distância</th></tr>';
        
        while ($row = pg_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $row['nome_quadra'] . '</td>';
            echo '<td>' . $row['zona'] . '</td>';
            echo '<td>' . $row['distancia_km'] . ' km</td>';
            echo '</tr>';
        }
        
        echo '</table>';
    } else {
        echo '<p>Nenhuma quadra encontrada neste raio.</p>';
    }
    echo '</div>';

    pg_close($conn);
    ?>

    <div class="box" style="background: #e8f5e9;">
        <h2>Conclusão</h2>
        <p>Se todos os testes acima passaram, seu PostgreSQL + PostGIS está funcionando perfeitamente!</p>
        <p><strong>Próximos passos:</strong></p>
        <ol>
            <li>Atualizar o arquivo contact.html para usar processa_contato_pg.php</li>
            <li>Atualizar services.php para usar api_quadras_pg