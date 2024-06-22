
<?php if ($total_pages > 1) : ?>
    <div class="tablenav-pages">
            <span class="pagination-links">
                <a class="first-page page-numbers" href="#" data-page="1">&laquo;</a>
                <a class="prev-page page-numbers" href="#"
                   data-page="<?php echo max(1, $current_page - 1); ?>">&lsaquo;</a>
                <dev class="pagination-numbers">
                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                        <a class="page-numbers <?php if ($current_page == $i): echo "current"; endif; ?>" href="#"
                           data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                </dev>
                <a class="next-page page-numbers" href="#" data-test="<?= $current_page?>"
                   data-page="<?php echo min($total_pages, $current_page + 1); ?>">&rsaquo;</a>
                <a class="last-page page-numbers" href="#" data-page="<?php echo $total_pages; ?>">&raquo;</a>
            </span>
    </div>
<?php endif; ?>