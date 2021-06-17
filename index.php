<?php

$data = [
	'prb' => [
		'img'   => 'img/prb.png',
		'title' => 'Программные решения для бизнеса'
	],
	'rmp' => [
		'img'   => 'img/rmp.png',
		'title' => 'Разработка мобильных приложений'
	],
	'kb'  => [
		'img'   => 'img/kb.png',
		'title' => 'Кибербезопасность'
	],
	'ct'  => [
		'img'   => 'img/ct.png',
		'title' => 'Цифровая трансформация'
	]
];

$action = isset($_GET['p']) ? $_GET['p'] : null;

$dd = '';
$r  = isset($_GET['r']);
if (isset($data[$action])) {
	$dd = $data[$action];

} else {

	$res = '';

	foreach ($data as $key => $datum) {
		$res .= "<a href='?p=$key'>{$datum['title']}</a> <br>";
	}

	echo '<html lang="ru"><title>WorldSkills Russia</title><body><h1>WorldSkills Russia timers</h1>' . $res . '</body></html>';

	die();
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <title><?php echo $dd['title']; ?> - WorldSkills Russia</title>
    <style>
        body {
            font-family: sans-serif;
            display: grid;
            height: 100vh;
            place-items: center;
            background: #329f42;
        }

        .base-timer {

            position: relative;
            width: 50vh;
            height: 60vh;
        }

        .base-timer__svg {
            transform: scaleX(-1);
        }

        .base-timer__circle {
            fill: none;
            stroke: none;
        }

        .base-timer__path-elapsed {
            stroke-width: 7px;
            stroke: grey;
        }

        .base-timer__path-remaining {
            stroke-width: 7px;
            stroke-linecap: round;
            transform: rotate(90deg);
            transform-origin: center;
            transition: 1s linear all;
            fill-rule: nonzero;
            stroke: currentColor;
        }

        .base-timer__path-remaining.green {
            color: rgb(65, 184, 131);
        }

        .base-timer__path-remaining.orange {
            color: orange;
        }

        .base-timer__path-remaining.red {
            color: red;
        }

        .base-timer__label {
            position: absolute;
            width: calc(100vh / 2);


            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25vw;
        }

        .footer {
            z-index: 1000;
        }
    </style>
</head>
<body>
<?php

$header = '<div class="header">' .
	'<img src="img/ws.png" style="height: 200px; width: auto"><img src="' . $dd['img'] . '" style="height: 200px; width: auto">' .
	'<h1>Осталось времени до окончания сессии:</h1></div>';
$app    = '<div id="app"></div>';

if ($r) {
	echo $app . $header;
} else {
	echo $header . $app;
}

?>
<script>

    let TIME_LIMIT = 90 * 60;


    const FULL_DASH_ARRAY = 283;
    const WARNING_THRESHOLD = 10;
    const ALERT_THRESHOLD = 5;

    const COLOR_CODES = {
        info: {
            color: "green"
        },
        warning: {
            color: "orange",
            threshold: WARNING_THRESHOLD
        },
        alert: {
            color: "red",
            threshold: ALERT_THRESHOLD
        }
    };


    let timePassed = 0;
    //   let timeLeft = TIME_LIMIT;
    let timerInterval = null;
    let remainingPathColor = COLOR_CODES.info.color;
    let isPaused = true;

    document.getElementById("app").innerHTML = `
<div class="base-timer">
  <span id="base-timer-label" class="base-timer__label">${formatTime(
        TIME_LIMIT
    )}</span>
</div>
`;

    startTimer();

    function onTimesUp() {
        isPaused = true;
       // clearInterval(timerInterval);
    }

    function renderTimer() {

        document.getElementById("base-timer-label").innerHTML = formatTime(
            TIME_LIMIT - timePassed
        );
    }

    function startTimer() {
        timerInterval = setInterval(function () {
            if (!isPaused) {
                timePassed += 1;

                if (TIME_LIMIT - timePassed === 0) {
                    onTimesUp();
                }

                renderTimer();

            }

        }, 1000);
    }

    function formatTime(time) {


        let hours = Math.floor(time / 3600);

        let minutes = Math.floor(time / 60) - hours * 60;

        let seconds = Math.floor(time % 60);


        // let days = Math.floor(hours / 24);

        // hours = hours - (days * 24);
        //minutes = minutes - (days * 24 * 60) - (hours * 60);
        // let seconds = time - (days * 24 * 60 * 60) - (hours * 60 * 60) - (minutes * 60);


        // const hours = Math.floor(time/360);

        // const minutes = Math.floor(time - hours*60);


        // let seconds = time % 60;

        if (minutes < 10) {
            minutes = '0' + minutes;
        }
        if (seconds < 10) {
            seconds = '0' + seconds;
        }

        return hours + ':' + minutes + ':' + seconds;
    }

    function resetTimer() {
        timePassed = 0;
        isPaused = true;
        renderTimer();
    }

    function triggerTimer() {
        if (!timerInterval) {
            timePassed = 0;
            startTimer();
        }
        isPaused = !isPaused;
    }

    function parseTime(t) {
        let d = 0;
        const time = t.match(/(\d+)(?::(\d\d))?\s*(p?)/);
        console.log(time);
        d += (parseInt(time[1]) + (time[3] ? 12 : 0)) * 3600;
        // d.setHours( parseInt( time[1]) + (time[3] ? 12 : 0) );
        // d.setMinutes( parseInt( time[2]) || 0 );
        d += (parseInt(time[2]) || 0) * 60;
        return d;
    }

    function updateTime(time) {

        // Let's get the value and break it up into hours, minutes, and seconds
        let times = parseTime(time);
        TIME_LIMIT = times;
        resetTimer();
    }


</script>

<div class="footer">
    <button id="stoptimer" onclick="triggerTimer()">Start/Stop timer</button>
    <button id="resettimer" onclick="resetTimer()">Reset timer</button>
    <input type="text" id="appt" name="appt" value="01:30" onchange="updateTime(this.value)">
    <button id="onehalfhour" onclick="updateTime('01:30')">01:30</button>
    <button id="onehouttwenty" onclick="updateTime('01:20')">01:20</button>
    <button id="halfhour" onclick="updateTime('00:30')">00:30</button>
    <button id="fifth" onclick="updateTime('00:15')">00:15</button>
</div>
</body>
</html>
