<?php

declare(strict_types=1);

require_once(__DIR__ . '/../database/category.class.php');
require_once(__DIR__ . '/../database/items.class.php');
require_once(__DIR__ . '/../database/brand.class.php');
require_once(__DIR__ . '/../database/size.class.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');



function drawCategory(PDO $db, Category $category, array $items) {
  $conditions = $db->query('SELECT * FROM Condition')->fetchAll(PDO::FETCH_ASSOC);
  ?>
  <h2><?=$category->name?></h2>
  
  <form method="GET" id="filterForm">
      <input type="hidden" name="categoryId" value="<?=$category->categoryId?>">
      <label for="price">Price (up to): </label>
      <input type="number" name="price" id="price" value="<?= isset($_GET['price']) ? intval($_GET['price']) : '' ?>" step="10">
      
      <label for="conditionId">Condition: </label>
      <select name="conditionId" id="conditionId">
          <option value="">All</option>
          <?php foreach ($conditions as $condition) { ?>
              <option value="<?=$condition['ConditionId']?>" <?= isset($_GET['conditionId']) && $_GET['conditionId'] == $condition['ConditionId'] ? 'selected' : '' ?>><?=$condition['Name']?></option>
          <?php } ?>
      </select>
      
      <button type="submit">Filter</button>
  </form>

  <section id="items">
      <?php foreach ($items as $item) { 
          $brand = Brand::extractBrandWithBrandId($db, $item->brandId);
          $size = Size::getSizebyId($db, $item->sizeId); 
          $imageSrc = "../images/items/item{$item->getItemId()}-0.png"; ?>
      <article>
          <img src="<?=$imageSrc?>" alt='Item Picture'>
          <a href="../pages/item.php?id=<?=$item->itemId?>"><?=$item->name?></a>
          <p class="info"><?=$brand->name?> / <?=$size->sizeName?> / <?=$item->price?>€ </p>
      </article>
      <?php } ?>
  </section>
<?php }
