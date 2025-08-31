document.addEventListener('DOMContentLoaded', function() {
    const departmentDropdown = document.getElementById('department');
    const programDropdown = document.getElementById('program');
    const moduleDropdown = document.getElementById('module');

    if (departmentDropdown && programDropdown) {
        departmentDropdown.addEventListener('change', function() {
            const departmentId = this.value;
            fetchPrograms(departmentId, programDropdown);
        });
    }

    if (programDropdown && moduleDropdown) {
        programDropdown.addEventListener('change', function() {
            const programId = this.value;
            fetchModules(programId, moduleDropdown);
        });
    }

    function fetchPrograms(departmentId, dropdown) {
        if (!departmentId) {
            dropdown.innerHTML = '<option value="">Select Program</option>';
            return;
        }
        fetch(`modules/fetch-programs.php?department_id=${departmentId}`)
            .then(response => response.json())
            .then(data => {
                dropdown.innerHTML = '<option value="">Select Program</option>';
                data.forEach(program => {
                    const option = document.createElement('option');
                    option.value = program.id;
                    option.textContent = program.name;
                    dropdown.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching programs:', error));
    }

    function fetchModules(programId, dropdown) {
        if (!programId) {
            dropdown.innerHTML = '<option value="">Select Module</option>';
            return;
        }
        fetch(`modules/fetch-modules.php?program_id=${programId}`)
            .then(response => response.json())
            .then(data => {
                dropdown.innerHTML = '<option value="">Select Module</option>';
                data.forEach(module => {
                    const option = document.createElement('option');
                    option.value = module.id;
                    option.textContent = module.name;
                    dropdown.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching modules:', error));
    }
});