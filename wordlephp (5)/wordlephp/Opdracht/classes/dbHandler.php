<?php
final class dbHandler
{        
    public $localhost = "localhost";
    public $username = "root";
    public $password = "";
    public $database = "wordle";
    public function connectDatabase(){
        try {
            $conn = new PDO("mysql:host={$this->localhost};dbname={$this->database}", $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected Successfully!";
            return $conn;
        } catch(PDOException $exception) {
            echo "Connection failed: " . $exception->getMessage();
            return null;
        }
    }

    public function selectAll(){
    
        try{
            $conn = $this->connectDatabase();
            $items = $conn->query("SELECT * FROM word INNER JOIN category ON category.categoryid = word.categoryid"); 
            $itemArray = array();

            foreach($items as $item){
                $itemArray[] = array(
                    'wordId' => $item['wordId'],
                    'name'=> $item['name'],
                    'text'=> $item['text']
                );
            }
            return $itemArray;
        } catch(PDOException $exception) {
            return null;
        }     
    }


    public function selectCategories()
    {
    
        try{
            $conn = $this->connectDatabase();  
            $items = $conn->query("SELECT * FROM category"); 
            $itemArray = array();

            foreach($items as $item){
                $itemArray[] = array(
                    'categoryId' => $item['categoryId'],
                    'name'=> $item['name']
                );
            }
            return $itemArray;
        } catch(PDOException $exception) {
            return null;
        } 
    }

    public function createWord($text, $categoryId)
    {
       
        try{
            $conn = $this->connectDatabase();
            $createSQL = $conn->prepare("INSERT INTO word(text, categoryId) VALUES (:text, :categoryId)");
            $createSQL->bindParam(':text', $text);
            $createSQL->bindParam(':categoryId', $categoryId);
            $createSQL->execute();

            $value = array(
                'text'=> $text,
                'categoryId'=> $categoryId
            );
            return $value;
        }
        catch(PDOException $exception){
           return null;
        }
    }
    public function selectOne($wordId){
   
        try{
            $conn = $this->connectDatabase();
            
            $selectedWord = $_POST['wordId'];
            
            $sql = $conn->prepare("SELECT * FROM word INNER JOIN category ON category.categoryid = word.categoryid WHERE wordId = :wordId");
            
            $sql->bindParam(':wordId', $selectedWord);

            $sql->execute();

            $selected_word = $sql->fetch(PDO::FETCH_ASSOC);

            return $selected_word;
        }
        catch(PDOException $exception){
            return null;
        }
    }

    public function updateWord($wordId, $text, $category){
       
        try{
            $conn = $this->connectDatabase();
       
            $updateSQL = $conn->prepare("UPDATE word SET text = :text, categoryId = :categoryId WHERE wordId = :wordId;");
            $updateSQL->bindParam(':text', $text);
            $updateSQL->bindParam(':wordId', $wordId);
            $updateSQL->bindParam(':categoryId', $category);
            
            $updateSQL->execute();

        
            return true;
        }
        catch(PDOException $exception){
            return null;
        }
    }

    public function deleteWord($id){
    
        try{
            $conn = $this->connectDatabase();
       
            $DeleteSQL = $conn->prepare("DELETE FROM word WHERE wordId = :wordId;");
            $DeleteSQL->bindParam(':wordId', $id);   
            $DeleteSQL->execute();

        
            return true;
        }
        catch(PDOException $exception){
            return null;
        }
    }
    }
?>