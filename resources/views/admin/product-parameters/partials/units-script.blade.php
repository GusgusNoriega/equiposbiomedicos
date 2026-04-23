<script>
    document.addEventListener('DOMContentLoaded', () => {
        const list = document.querySelector('[data-parameter-unit-list]');
        const addButton = document.querySelector('[data-parameter-unit-add]');
        const template = document.querySelector('[data-parameter-unit-template]');

        if (! list || ! addButton || ! template) {
            return;
        }

        let nextIndex = list.querySelectorAll('[data-parameter-unit-row]').length;

        const clearRow = (row) => {
            row.querySelectorAll('input').forEach((input) => {
                if (input.type === 'hidden') {
                    input.value = '';
                    return;
                }

                input.value = input.type === 'number' ? 10 : '';
            });
        };

        const attachHandlers = () => {
            list.querySelectorAll('[data-parameter-unit-remove]').forEach((button) => {
                button.onclick = () => {
                    const rows = list.querySelectorAll('[data-parameter-unit-row]');

                    if (rows.length === 1) {
                        clearRow(rows[0]);
                        return;
                    }

                    button.closest('[data-parameter-unit-row]')?.remove();
                };
            });
        };

        addButton.addEventListener('click', () => {
            const index = nextIndex;
            const html = template.innerHTML
                .replace(/__ID_NAME__/g, `units[${index}][id]`)
                .replace(/__NAME_NAME__/g, `units[${index}][name]`)
                .replace(/__SYMBOL_NAME__/g, `units[${index}][symbol]`)
                .replace(/__CODE_NAME__/g, `units[${index}][code]`)
                .replace(/__SORT_NAME__/g, `units[${index}][sort_order]`);

            list.insertAdjacentHTML('beforeend', html);
            nextIndex += 1;
            attachHandlers();
        });

        attachHandlers();
    });
</script>
