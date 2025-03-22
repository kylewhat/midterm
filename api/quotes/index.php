<?

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

switch($method){
    case 'OPTIONS': 
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
        break; 
    
        case 'GET':
            if(isset($_GET['id'])){
                include 'read_single.php';
            } else {
                include 'read.php';
            }

            $output = ob_get_clean();
            return $output;
            break;

        case 'POST':
            include 'create.php';
            $output = ob_get_clean();
            return $output;
            break;

        case 'PUT':
            include 'update.php';
            $output = ob_get_clean();
            return $output;
            break;

        case 'DELETE':
            include 'delete.php';
            $output = ob_get_clean();
            return $output;
            break;
        
}
