<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>test2</title>
    <style>
        li {
            list-style-type: none;
        }
        ul {
            padding-left: 20px;
        }
        ul span {
            cursor: pointer;
        }
    </style>
    <script>
        function ShowCategories(id) {
            let item = document.getElementById('cat_ul_'+id);

            if (item.style.display === 'block') {
                item.style.display = 'none';
                event.target.innerHTML = "&#11208";
            }
            else {
                item.style.display = 'block';
                event.target.innerHTML = "&#11206";
            }
        }
    </script>
</head>
<body>
<?php

class TreeCategories
{
    public function getArray()
    {
        $db = mysqli_connect("localhost","root","12345","test2");
        if ($query = mysqli_query($db,"SELECT * FROM categories ORDER BY categories_id"))
        {
            while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC))
            {
                $arr[$row['categories_id']] = $row;
            }
            $result = [];
            foreach ($arr as $id => & $value) {
                if (!$value['parent_id']) {
                    $result[$id] = & $value;
                } else {
                    $arr[$value['parent_id']]['children'][$id] = & $value;
                }
            }
            return $result;
        }
        else return null;
    }

    public function buildMenu($arr)
    {
        foreach ($arr as $element) break;
        $html = '<ul id="cat_ul_'.$element['parent_id'].'" style="display: none">';

        foreach ($arr as $item)
        {
            if (isset($item['children']))
            {
                $html .= '<li><span onclick="ShowCategories('.$item['categories_id'].')">&#11208;</span>'.$item['categories_id'].' = {array}['.count($item['children']).']';
                $html .= $this->buildMenu($item['children']);
                $html .= '</li>';
            }
            else
            {
                $html .= '<li>'.$item['categories_id'].' = '.$item['categories_id'].'</li>';
            }
        }
        $html .= '</ul>';
        return $html;
    }
}

//$start = microtime(true); //onclick="ShowCategories('.$element['parent_id'].')"
$arr = new TreeCategories();
echo '<ul><li><span onclick="ShowCategories(0)"> &#11208; </span>$return = {array}['.count($arr->getArray()).']';
echo $arr->buildMenu($arr->getArray());
echo '</li></ul>';
//$start = microtime(true) - $start;
//echo "<br>".$start;
?>
</body>
</html>
