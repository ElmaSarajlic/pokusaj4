<?php

class MidtermDao
{

  private $conn;

  public function __construct()
  {
    try {

      /** TODO
       * List parameters such as servername, username, password, schema. Make sure to use appropriate port
       */
      $host = 'containers-us-west-124.railway.app';
      $username = 'root';
      $password = '0Um2qsa4IhDti9jMPoIX';
      $port = '8021';
      $schema = 'prep2';


      /*options array neccessary to enable ssl mode - do not change*/
      /* $options = array(
        	PDO::MYSQL_ATTR_SSL_CA => 'https://drive.google.com/file/d/1IUuXFceXGAH_rydvtJwW5jYzlnZ9FWBw/view?usp=sharing',
        	PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,

        ); */

      /** TODO
       * Create new connection
       * Use $options array as last parameter to new PDO call after the password
       */
      $this->conn = new PDO("mysql:host=$host;port=$port;dbname=$schema", $username, $password);

      // set the PDO error mode to exception
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      echo "Connected successfully";
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
  }

  /** TODO
   * Implement DAO method used to get cap table
   */
  public function cap_table()
  {
    $query = "SELECT 
    cap.share_class_id, 
    sc.description as class_description, 
    cap.share_class_category_id, 
    scc.description as category_description,
    cap.investor_id,
    CONCAT(i.first_name, ' ', i.last_name) as investor_name,
    cap.diluted_shares
    FROM cap_table cap
    JOIN share_classes sc ON cap.share_class_id = sc.id
    JOIN share_class_categories scc ON cap.share_class_category_id = scc.id
    JOIN investors i ON cap.investor_id = i.id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];
    foreach ($data as $row) {
      $share_class_id = $row['share_class_id'];
      $category_id = $row['share_class_category_id'];
      $investor_id = $row['investor_id'];

      $result[$share_class_id]['class'] = $row['class_description'];
      $result[$share_class_id]['categories'][$category_id]['category'] = $row['category_description'];
      $result[$share_class_id]['categories'][$category_id]['investors'][] = [
        'investor' => $row['investor_name'],
        'diluted_shares' => $row['diluted_shares']
      ];
    }

    $result = array_values($result);

    return $result;
  }

  /** TODO
   * Implement DAO method used to add cap table record
   */
  public function add_cap_table_record($id, $share_class_id, $share_class_category_id, $investor_id, $diluted_shares)
  {
    $sql = "INSERT INTO cap_table (id, share_class_id, share_class_category_id, investor_id, diluted_shares)
    VALUES (?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$id, $share_class_id, $share_class_category_id, $investor_id, $diluted_shares]);

    // return inserted data as an associative array
    return [
      'id' => $id,
      'share_class_id' => $share_class_id,
      'share_class_category_id' => $share_class_category_id,
      'investor_id' => $investor_id,
      'diluted_shares' => $diluted_shares,
    ];
  }

  /** TODO
   * Implement DAO method to return list of categories with total shares amount
   */
  public function categories()
  {
    $query = "select c.description as category, sum(ct.diluted_shares) as total_shares
    from share_class_categories c
    join cap_table ct on ct.share_class_category_id = c.id
    group by c.description";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  /** TODO
   * Implement DAO method to delete investor
   */
  public function delete_investor($id)
  {
    $stmt = $this->conn->prepare("DELETE FROM investors WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->execute();
  }
}