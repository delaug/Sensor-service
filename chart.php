<?
    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);

    require_once 'src/DB.php';
    use src\DB;

    // DB
    $DB = new DB();
    $arData = $DB->getList();

    // Read data
    $arLabels = array_column($arData, 'created_at');
    $arTemperature = array_column($arData, 'temperature');
    $arHumidity = array_column($arData, 'humidity');
    $arPressure = array_column($arData, 'pressure');

    // Formatting
    $arLabels = array_map(function ($val) {
        return date('m.d H:i', strtotime($val));
    }, $arLabels);

    // Convertation to mm Hg
    $arPressure = array_map(function ($val) {
        return $val / 1.333;
    }, $arPressure);

    // Current values
    $currentTemperature = current($arTemperature);
    $currentHumidity = current($arHumidity);
    $currentPressure = number_format(current($arPressure), 2);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $currentTemperature.' °C '.$currentHumidity.' % '.$currentPressure.' mmHg'?></title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div>
        <canvas id="chartT" style="height:32vh; width:100%"></canvas>
        <canvas id="chartH" style="height:32vh; width:100%"></canvas>
        <canvas id="chartP" style="height:32vh; width:100%"></canvas>
    </div>
    <script>
        const config = {
            type: 'line',
            options: {
                responsive: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Title'
                    },
                },
            }
        };

        var configT = Object.assign({}, config, {
            data: {
                labels: <?=json_encode($arLabels)?>,
                datasets: [
                    {
                        label: 'Temperature',
                        backgroundColor: 'rgb(255, 99, 132)',
                        borderColor: 'rgb(255, 99, 132)',
                        borderWidth: 1,
                        radius: 0,
                        data: <?=json_encode($arTemperature)?>,
                    }
                ]
            }
        });
        var configH = Object.assign({}, config, {
            data: {
                labels: <?=json_encode($arLabels)?>,
                datasets: [
                    {
                        label: 'Humidity',
                        backgroundColor: 'rgb(99,102,255)',
                        borderColor: 'rgb(99,102,255)',
                        borderWidth: 1,
                        radius: 0,
                        data: <?=json_encode($arHumidity)?>,
                    }
                ]
            }
        });

        var configP = Object.assign({}, config, {
            data: {
                labels: <?=json_encode($arLabels)?>,
                datasets: [
                    {
                        label: 'Pressure',
                        backgroundColor: 'rgb(21,147,70)',
                        borderColor: 'rgb(21,147,70)',
                        borderWidth: 1,
                        radius: 0,
                        data: <?=json_encode($arPressure)?>,
                    }
                ]
            }
        });

        configT.options.plugins.title.text = <?=$currentTemperature?>+' °C';
        var chartT = new Chart(
            document.getElementById('chartT'),
            configT
        );

        configT.options.plugins.title.text = <?=$currentHumidity?>+' %';
        var chartH = new Chart(
            document.getElementById('chartH'),
            configH
        );

        configT.options.plugins.title.text = <?=$currentPressure?>+' mmHg';
        var chartP = new Chart(
            document.getElementById('chartP'),
            configP
        );
    </script>
</body>
</html>