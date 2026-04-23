<script>
    document.addEventListener('DOMContentLoaded', () => {
        const list = document.querySelector('[data-specification-list]');
        const addButton = document.querySelector('[data-specification-add]');
        const template = document.querySelector('[data-specification-template]');

        if (! list || ! addButton || ! template) {
            return;
        }

        let nextIndex = list.querySelectorAll('[data-specification-row]').length;

        const attachRemoveHandlers = () => {
            list.querySelectorAll('[data-specification-remove]').forEach((button) => {
                button.onclick = () => {
                    const rows = list.querySelectorAll('[data-specification-row]');

                    if (rows.length === 1) {
                        rows[0].querySelectorAll('input').forEach((input) => {
                            input.value = '';
                        });

                        return;
                    }

                    button.closest('[data-specification-row]')?.remove();
                };
            });
        };

        addButton.addEventListener('click', () => {
            const index = nextIndex;
            const html = template.innerHTML
                .replace('__NAME__', `specifications[${index}][label]`)
                .replace('__VALUE__', `specifications[${index}][value]`)
                .replace('__UNIT__', `specifications[${index}][unit]`)
                .replace('__SORT__', `specifications[${index}][sort_order]`);

            list.insertAdjacentHTML('beforeend', html);
            nextIndex += 1;
            attachRemoveHandlers();
        });

        attachRemoveHandlers();
    });
</script>
