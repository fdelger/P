<!DOCTYPE HTML>
<html>  
<body>

<h1>Please submit contact information</h1>

<form method="post">
    First Name:  <input type="text" name="firstname" required/><br />
    Last Name: <input type="text" name="lastname" required/><br />
    Address: <input type="text" name="address" /><br />
    Email: <input type="email" placeholder="Please enter your email" name="email" required/><br />
    Phone Number: <input type="text" name="phone" /><br />
    <input type="submit" name="submit" value="Submit" />
</form>
    

    <?php
        class Contact {
            public $firstname;
            private $lastname;
            private $address;
            private $email;
            private $phone;
            // Constructor
            public function __construct() {
                $this->firstname = $_POST["firstname"];
                $this->lastname = $_POST["lastname"];
                $this->address = $_POST["address"];
                $this->email = $_POST["email"];
                $this->phone = $_POST["phone"];
        
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
        if (isset($_POST["firstname"]))
        {   
            
            $contact = new Contact();
            
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

                $stmt->bind_param('ssssi',$first,$last,$addr,$mail,$num);
                if ($stmt->execute()) {
                    echo "Contact Successfully created";
                } else {
                    echo "Error during contact creation";
                }
                
            }
           
            /*
            if ($connection->query($sql) === TRUE) {
                echo "New record created successfully";
            }   else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }     
            */
            unset($contact);
            $connection->close();
        } 
        
        

    ?>
    
    

</body>
</html>