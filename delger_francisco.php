<!DOCTYPE HTML>
<html>  
<body>

<h1>Please submit contact information manually.</h1><br />
<h2>You can also inject a file by doing a GET on the URL Directly</h2><br />   
<h3>Example: http://localhost/delger_francisco.php?xml=test.xml</h3><br />
<h4>Note: The file must be in the following path 'C:/xampp/htdocs/YOURFILE.xml'</h4>

<form method="post">
    First Name:  <input type="text" name="firstname" required/><br />
    Last Name: <input type="text" name="lastname" required/><br />
    Address: <input type="text" name="address" /><br />
    Email: <input type="email" placeholder="Please enter your email" name="email" required/><br />
    Phone Number: <input type="text" maxlength="15" name="phone" /><br />
    <input type="submit" name="submitForm" value="Submit" />
</form>
    
    <?php
        class Contact {
            private $firstname;
            private $lastname;
            private $address;
            private $email;
            private $phone;
            /* Create two constructors. One for POST request with manual input
             Second Constructor for GET Request with URL Injection
             I'm doing this to simulate method overloading from Java, C#, etc. As I don't want two separate classes.
            */
            protected function __construct(){        
            }
    
            public static function postContact() {
                $c = new Contact();
                $c->firstname = $_POST["firstname"];
                $c->lastname = $_POST["lastname"];
                $c->address = $_POST["address"];
                $c->email = $_POST["email"];
                $c->phone = $_POST["phone"];
                return $c;
            }
            
            public static function getContact($firstName, $lastName, $mail, $addr, $phone) {
                $c = new Contact();
                $c->firstname = $firstName;
                $c->lastname = $lastName;
                $c->email = $mail;
                $c->address = $addr;
                $c->phone = $phone;
                return $c;
            }
            
            public function getFirst() {
                return $this->firstname;
            }
            public function getLast() {
                return $this->lastname;
            }
            public function getAddr() {
                return $this->address;
            }
            public function getEmail() {
                return $this->email;
            }
            public function getPhone() {
                return $this->phone;
            }
            // I'm grabbing the information from the form, helper function
            public function printContact() {
                echo"This is the contact information $this->firstname,
                 $this->lastname, $this->address, $this->email, $this->phone"; 
            }
        }
    
    
        // Connection Setup
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "exercise";
    
        // Create connection
        $connection = new mysqli($servername, $username, $password, $dbname);
        if ($connection->connect_error) {
                die("Connection to the databse failed: " . $connection->connect_error);
        } 
        
        // Create object if form has been submitted
        if (isset($_POST["submitForm"]))
        {   
            
            $contact =  Contact::postContact();
            
            /* Create variables because it complains if I don't pass by reference doing something like
                $stmt->bind_param('ssssi',$contact->getFirst()...
                The contact creation still goes through, but I'd rather get rid of the warning 
            */
            
            $first = $contact->getFirst();
            $last = $contact->getLast();
            $addr = $contact->getAddr();
            $mail = $contact->getEmail();
            $num = $contact->getPhone();
            
            // Question Marks for binding of parameters
            
            $sql = "INSERT INTO Contact (firstname, lastname, address, email, phone)
             VALUES (?,?,?,?,?)";
            
            // We do prepare() so that it understands that values will substitute the question marks
            
            if ($stmt = $connection->prepare($sql)) {

                $stmt->bind_param('sssss',$first,$last,$addr,$mail,$num);
                if ($stmt->execute()) {
                    echo "Contact Successfully created";
                } else {
                    echo "Error during contact creation";
                }
                
            }
           
            unset($contact);
            $connection->close();
        } 
    
        if($_GET)
        {
            
            // Build path from GET/URL Injection
            $myFile = 'C:/xampp/htdocs/' . $_GET['xml']; 
            $xmlcont = simplexml_load_file($myFile);
            
            // Check I have loaded correctly
            //echo $xmlcont->lastname;
            //print_r($xmlcont);
            
            // Create contact object
            
            $getContact = Contact::getContact($xmlcont->firstname,$xmlcont->lastname,
                $xmlcont->email,$xmlcont->address,$xmlcont->phone);
            
            // Basic Required Information check
            
            if ($getContact->getFirst() == null || $getContact->getLast() == null || $getContact->getEmail() == null) {
                echo "Invalid XML Content. Please check there's a firstname, lastname, and Email included";
            }
        
            //I'm using w3Schools code snippet with some modifications made by me to check XML
            
            if(!filter_var($getContact->getEmail(), FILTER_VALIDATE_EMAIL)) {
                  echo "Invalid email format. Please fix and try again."; 
              } else {
                
                 echo "Valid Email Address! Creating Contact ";
                 $first = $getContact->getFirst();
                 $last = $getContact->getLast();
                 $addr = $getContact->getAddr();
                 $mail = $getContact->getEmail();
                 $num = $getContact->getPhone();
            
                // Question Marks for binding of parameters
            
                 $sql = "INSERT INTO Contact (firstname, lastname, address, email, phone)
                    VALUES (?,?,?,?,?)";
            
                // We do prepare() so that it understands that values will substitute the question marks
            
                 if ($stmt = $connection->prepare($sql)) {

                    $stmt->bind_param('sssss',$first,$last,$addr,$mail,$num);
                    if ($stmt->execute()) {
                        echo "Contact Successfully created through URL/XML injection";
                    } else {
                        echo "Error during contact creation";
                    }
                
                }
                
                unset($getContact);
                $connection->close();
            }
            
           
        }
        
        

    ?>
    
    

</body>
</html>