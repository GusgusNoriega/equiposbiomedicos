<script>
    document.addEventListener('DOMContentLoaded', () => {
        const list = document.querySelector('[data-parameter-value-list]');
        const addButton = document.querySelector('[data-parameter-value-add]');
        const template = document.querySelector('[data-parameter-value-template]');
        const catalogNode = document.querySelector('[data-parameter-catalog]');

        if (! list || ! addButton || ! template || ! catalogNode) {
            return;
        }

        const catalog = JSON.parse(catalogNode.textContent || '[]');
        let nextIndex = list.querySelectorAll('[data-parameter-value-row]').length;

        const escapeHtml = (value) => String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');

        const parameterOptions = (selectedId = '') => {
            let options = '<option value="">Selecciona un parametro</option>';

            catalog.forEach((parameter) => {
                const selected = String(parameter.id) === String(selectedId) ? ' selected' : '';
                options += `<option value="${parameter.id}" data-value-type="${escapeHtml(parameter.value_type)}"${selected}>${escapeHtml(parameter.name)} (${escapeHtml(parameter.code)})</option>`;
            });

            return options;
        };

        const unitOptions = (parameterId, selectedUnitId = '') => {
            let options = '<option value="">Sin unidad</option>';
            const parameter = catalog.find((item) => String(item.id) === String(parameterId));

            if (! parameter) {
                return options;
            }

            parameter.units.forEach((unit) => {
                const selected = String(unit.id) === String(selectedUnitId) ? ' selected' : '';
                const label = unit.symbol ? `${unit.name} (${unit.symbol})` : unit.name;
                options += `<option value="${unit.id}"${selected}>${escapeHtml(label)}</option>`;
            });

            return options;
        };

        const parameterHint = (parameterId) => {
            const parameter = catalog.find((item) => String(item.id) === String(parameterId));

            if (! parameter) {
                return 'Selecciona un parametro para cargar sus unidades.';
            }

            return parameter.value_type === 'number'
                ? 'Valor numerico recomendado para filtros por rango.'
                : 'Valor de texto libre para filtros exactos.';
        };

        const syncRow = (row, preserveUnit = true) => {
            const parameterSelect = row.querySelector('[data-parameter-select]');
            const unitSelect = row.querySelector('[data-parameter-unit-select]');
            const hint = row.querySelector('[data-parameter-type-hint]');
            const selectedUnit = preserveUnit ? unitSelect.value : '';

            parameterSelect.innerHTML = parameterOptions(parameterSelect.value);
            unitSelect.innerHTML = unitOptions(parameterSelect.value, selectedUnit);
            hint.textContent = parameterHint(parameterSelect.value);
        };

        const clearRow = (row) => {
            row.querySelectorAll('input').forEach((input) => {
                input.value = input.type === 'number' ? 10 : '';
            });

            const parameterSelect = row.querySelector('[data-parameter-select]');
            const unitSelect = row.querySelector('[data-parameter-unit-select]');

            if (parameterSelect) {
                parameterSelect.value = '';
            }

            if (unitSelect) {
                unitSelect.value = '';
            }

            syncRow(row, false);
        };

        const attachHandlers = () => {
            list.querySelectorAll('[data-parameter-value-row]').forEach((row) => {
                const removeButton = row.querySelector('[data-parameter-value-remove]');
                const parameterSelect = row.querySelector('[data-parameter-select]');

                if (removeButton) {
                    removeButton.onclick = () => {
                        const rows = list.querySelectorAll('[data-parameter-value-row]');

                        if (rows.length === 1) {
                            clearRow(rows[0]);
                            return;
                        }

                        row.remove();
                    };
                }

                if (parameterSelect) {
                    parameterSelect.onchange = () => syncRow(row, false);
                }

                syncRow(row);
            });
        };

        addButton.addEventListener('click', () => {
            const index = nextIndex;
            const html = template.innerHTML
                .replace(/__PARAMETER_NAME__/g, `parameter_values[${index}][product_parameter_id]`)
                .replace(/__VALUE_NAME__/g, `parameter_values[${index}][value]`)
                .replace(/__UNIT_NAME__/g, `parameter_values[${index}][product_parameter_unit_id]`)
                .replace(/__SORT_NAME__/g, `parameter_values[${index}][sort_order]`);

            list.insertAdjacentHTML('beforeend', html);
            nextIndex += 1;
            attachHandlers();
        });

        attachHandlers();
    });
</script>
