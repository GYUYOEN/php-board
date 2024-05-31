<table class="table">
    <thead>
        <tr>
        <th scope="col">번호</th>
        <th scope="col">글쓴이</th>
        <th scope="col">제목</th>
        <th scope="col">등록일</th>
        </tr>
    </thead>
    <tbody id="board_list">
        <?php
        foreach($list as $ls) {
        ?>
        <tr>
            <th scope="row"><?php echo $ls->bid;?></th>
            <td><?php echo $ls->userid;?></td>
            <td><?php echo $ls->subject;?></td>
            <td><?php echo $ls->regdate;?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>