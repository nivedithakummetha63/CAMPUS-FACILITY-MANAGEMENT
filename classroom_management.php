<?php
// Sample data for blocks, floors, and classrooms
$blocks = [
    'Main Block' => ['Ground Floor', 'First Floor', 'Second Floor', 'Third Floor'],
    'Block A' => ['Ground Floor', 'First Floor', 'Second Floor', 'Third Floor'],
    'Block B' => ['Ground Floor', 'First Floor', 'Second Floor', 'Third Floor'],
    'Block C' => ['Ground Floor', 'First Floor', 'Second Floor', 'Third Floor']
];

$classrooms = [
    'Main Block' => [
        'Ground Floor' => ['seminar hall'],
        'First Floor' => ['Room 101','Room 102', 'COMPUTER LAB'],
        'Second Floor' => ['Room 201', 'Room 202','Room 203','Room 204','PHYSICAL LAB ','CHEMISTRY LAB','Room 205','Room 206','Room 207'],
        'Third Floor' => ['Room 301', 'Room 302','Room 304', 'Room 305', 'Room 306']
    ],
    'Block A' => [
        'Ground Floor' => ['VIRTUAL LAB', 'Room A01', 'ROOM A02' ],
        'First Floor' => ['Room A11','Room A12','Room A13','Room A14','Room 15','Room A16','Room A17','Room A18'],
        'Second Floor' => ['BEEE LAB','ELECTRICAL LAB'],
        'Third Floor' => ['Room 403', 'Room 404']
    ],
    'Block B' => [
        'Ground Floor' => ['SEMINAR HALL', 'BEEE LAB'],
        'First Floor' => ['COMPUTER LAB', 'Room B11','Room B12','Room B13','Room B14'],
        'Second Floor' => ['Room B21','Room B22','Room B23','Room B24','Room B25','Room B26','Room B27','Room B28'],
        'Third Floor' => ['Room B31','Room B32','Room B33','Room B34','Room B35','Room B36','Room B37','Room B38']
    ],
    'Block C' => [
        'Ground Floor' => ['COMPUTER LAB'],
        'First Floor' => ['COMPUTER LAB'],
        'Second Floor' => ['DIGITAL LIBRARY'],
        'Third Floor' => ['DIGITAL LIBRARY']
    ]
];

// Initialize variables
$block = '';
$floor = '';
$classroomsForFloor = [];
$searchQuery = '';
$searchResults = [];
$selectedRoom = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['block'])) {
        $block = $_POST['block'];
    }

    if (isset($_POST['floor'])) {
        $floor = $_POST['floor'];
    }

    if (isset($_POST['search'])) {
        $searchQuery = $_POST['search'];

        if ($searchQuery) {
            foreach ($blocks as $blockName => $floors) {
                foreach ($floors as $floorName) {
                    foreach ($classrooms[$blockName][$floorName] as $classroom) {
                        if (stripos($classroom, $searchQuery) !== false) {
                            $searchResults[] = [
                                'block' => $blockName,
                                'floor' => $floorName,
                                'classroom' => $classroom
                            ];
                        }
                    }
                }
            }
        }
    }

    if ($block && $floor) {
        $classroomsForFloor = $classrooms[$block][$floor];
    }

    if (isset($_POST['room'])) {
        $selectedRoom = $_POST['room']; // Store the selected room for the complaint form
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classroom Management</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('https://content3.jdmagicbox.com/comp/anantapur/n4/9999p8554.8554.190501113552.j4n4/catalogue/srit-engineering-college-anantapur-engineering-colleges-hvssgz0zd8.jpg');
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 40px auto;
            background-color: rgba(255, 255, 255, 0.5);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 150px;
        }

        h1 {
            font-size: 48px;
            font-weight: bold;
            color: #fff;
            text-align: center;
            background: linear-gradient(135deg, #3A7DFF, #1f5ca0);
            -webkit-background-clip: text;
            background-clip: text;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.4);
            padding: 20px;
            margin-bottom: 20px;
        }

        .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            width: 100%;
        }

        .search-bar input {
            width: 80%; /* Adjust width to make input larger */
            padding: 14px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box; /* Ensures padding doesn't overflow */
        }

        .search-bar input:focus {
            border-color: #3A7DFF;
            outline: none;
        }

        .search-bar button {
            width: 18%; /* Adjust width of the button */
            padding: 14px;
            margin-left: 10px;
            background-color: #3A7DFF;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        .selection-container button,
        .classrooms li a {
            display: inline-block;
            padding: 14px 25px;
            margin: 10px 5px;
            background-color: #3A7DFF;
            color: #fff;
            font-size: 16px;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
        }

        .classrooms ul {
            list-style-type: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .classrooms li {
            padding: 15px;
            margin: 10px;
            background-color: #f7f9fc;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 200px;
            text-align: center;
        }

        .complaint-form {
            display: none;
            margin-top: 40px;
            text-align: center;
        }

        iframe {
            width: 100%;
            height: 500px;
            border-radius: 10px;
        }
    </style>
<script>
    function showComplaintForm(classroom) {
        document.getElementById('selectedClassroom').innerText = classroom;
        document.getElementById('JotFormIFrame-250102834922449').src = "https://www.jotform.com/app/250102834922449?room=" + encodeURIComponent(classroom) + "&appEmbedded=1";
        document.querySelector('.complaint-form').style.display = 'block';
    }
</script>
</head>
<body>

<div class="container">
    <div class="header">
        <img src="https://www.srit.ac.in/wp-content/uploads/2021/12/SRIT-LOGO.jpeg">
    </div>
    <h1>Classroom Management</h1>

    <form method="POST" action="" class="search-bar">
        <input type="text" name="search" placeholder="Search for a classroom..." value="<?= htmlspecialchars($searchQuery) ?>">
        <button type="submit">Search</button>
    </form>

    <?php if ($searchQuery): ?>
        <h2>Search Results</h2>
        <ul class="classrooms">
            <?php foreach ($searchResults as $result): ?>
                <li><a href="#" onclick="showComplaintForm('<?= $result['classroom'] ?>')">
                    <strong><?= $result['classroom'] ?></strong><br><?= $result['block'] ?> - <?= $result['floor'] ?>
                </a></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="" class="selection-container">
        <h2>Select a Block</h2>
        <?php foreach ($blocks as $blockName => $floors): ?>
            <button type="submit" name="block" value="<?= $blockName ?>"><?= $blockName ?></button>
        <?php endforeach; ?>
    </form>

    <?php if ($block): ?>
        <form method="POST" action="" class="selection-container">
            <h2>Select a Floor in <?= $block ?></h2>
            <input type="hidden" name="block" value="<?= $block ?>">
            <?php foreach ($blocks[$block] as $floorName): ?>
                <button type="submit" name="floor" value="<?= $floorName ?>"><?= $floorName ?></button>
            <?php endforeach; ?>
        </form>
    <?php endif; ?>

    <?php if ($block && $floor): ?>
        <div class="classrooms">
            <h2>Classrooms on <?= $floor ?> in <?= $block ?>:</h2>
            <ul>
                <?php foreach ($classroomsForFloor as $classroom): ?>
                    <li><a href="#" onclick="showComplaintForm('<?= $classroom ?>')"><?= $classroom ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="complaint-form">
        <h2>Submit a Complaint for: <span id="selectedClassroom"></span></h2>
        <iframe id="JotFormIFrame-250102834922449" 
                title="College Facility &amp; Support Service Satisfaction App" 
                allow="geolocation; microphone; camera" 
                src="https://www.jotform.com/app/250102834922449?room=<?= urlencode($selectedRoom) ?>&appEmbedded=1" 
                style="height:600px; width:375px; border: 0;"></iframe>
    </div>
</div>

<div class="footer">
    <p>&copy; 2025 Classroom Management System. All rights reserved. </br>
<B> DEVELOPED BY BATCH-2026</B></p>
</div>

</body>
</html> 