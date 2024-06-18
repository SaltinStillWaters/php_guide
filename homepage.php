<?php
@session_start();

require_once('utils/page_controller.php');
require_once('db/database.php');
require_once('misc/misc.php');

PageController::init(false);

$conn = Database::establishConnection();
$rows = Database::getRows($conn, 'user');

function getZodiac(&$arr)
{
    $day = (int) (explode('-', $arr['BIRTHDAY'])[2]);
    $month = (int) (explode('-', $arr['BIRTHDAY'])[1]) - 1;

    $zodiacs = ['Aquarius', 'Pisces', 'Aries', 'Taurus', 'Gemini', 'Cancer', 'Leo', 'Virgo', 'Libra', 'Scorpio', 'Sagittarius', 'Capricorn'];
    $startDates = ['20', '19', '21', '20', '21', '21', '23', '23', '23', '23', '22', '22'];

    $month = ($day < $startDates[$month]) ? --$month : $month;

    $arr['BIRTHDAY'] .= ' (' . $zodiacs[$month] . ')';
}

function getNetwork(&$arr)
{
    global $prefixNetworks;
    $prefix = substr($arr['MOBILE_NUMBER'], 0, 4);
    $network = Misc::$prefixNetworks[$prefix];

    $arr['MOBILE_NUMBER'] .= ' (' . $network . ')';
}

function getDomain(&$arr)
{
    $domain = explode('@', $arr['EMAIL'])[1];

    $arr['EMAIL'] .= ' (' . $domain . ')';
}
function renderTable($rows)
{
    if (empty($rows)) 
    {
        return '<p>No data available.</p>';
    }
    
    $html = '<table class="data-table">';

    foreach ($rows as $row) 
    {
        $html .= '<tbody>';

        $html .= '<tr><th colspan="2">User Information</th></tr>';
        foreach ($row as $key => $data) 
        {
            if ($key === 'USER_ID') 
            {
                continue;
            }

            if ($key === 'UNIT_NUMBER') 
            {
                break;
            }

            $html .= "<tr> <td>$key</td> <td>$data</td> </tr>";
        }

        $html .= '<tr><th colspan="2">Address Information</th></tr>';
        $temp = 0;
        foreach ($row as $key => $data) 
        {
            if ($key === 'UNIT_NUMBER') 
            {
                $temp = 1;
            }
            if (!$temp) {
                continue;
            }
            if ($key === 'DEPENDENT_FIRST_NAME') 
            {
                break;
            }

            $html .= "<tr> <td>$key</td> <td>$data</td> </tr>";
        }

        $html .= '<tr><th colspan="2">Address Information</th></tr>';
        $temp = 0;
        foreach ($row as $key => $data) 
        {
            if ($key === 'DEPENDENT_FIRST_NAME') 
            {
                $temp = 1;
            }
            if (!$temp) {
                continue;
            }

            $html .= "<tr> <td>$key</td> <td>$data</td> </tr>";
        }
        $html .= '</tbody>';
    }

    $html .= '</table>';

    return $html;
}

echo '
<style>
    .data-table 
    {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .data-table th, .data-table td 
    {
        border: 1px solid #ddd;
        padding: 8px;
    }
    .data-table th 
    {
        background-color: #f2f2f2;
        text-align: left;
    }
    .data-table tr:nth-child(even) 
    {
        background-color: #f9f9f9;
    }
    .data-table tr:hover 
    {
        background-color: #ddd;
    }
</style>
';


getZodiac($rows[0]);
getNetwork($rows[0]);
getDomain($rows[0]);

echo renderTable($rows);
?>

<a href = 'index.php'> Click to Register Again</a>
CREATED BY MYCHAL ANDRES B. PEJANA