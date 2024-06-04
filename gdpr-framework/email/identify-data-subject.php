<p>
    <?= esc_html__('Ktoś poprosił o dostęp do Twoich danych na', 'gdpr-framework'); ?> <?= esc_html($siteName); ?> <br/>
    <?= esc_html__('Jeśli to był błąd, po prostu zignoruj ten e-mail i nic się nie stanie.', 'gdpr-framework'); ?> <br/>
    <?= esc_html__('Aby zarządzać swoimi danymi, odwiedź następujący adres:', 'gdpr-framework'); ?> <br/>
    <a href="<?= esc_url($identificationUrl); ?>">
        <?= esc_url($identificationUrl); ?>
    </a>
</p>
<p>
    <?= esc_html__('Ten link jest aktywny tylko przez 15 minut.', 'gdpr-framework'); ?>
</p>
