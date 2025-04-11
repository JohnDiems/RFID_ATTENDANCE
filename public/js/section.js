// Define the available sections for each grade
var gradeSections = {
    'GRADE 7': ['ST. AGNES', 'ST. ANNE', 'ST. BERNADETTE'],
    'GRADE 8': ['OLGC', 'OLHR', 'OLMC'],
    'GRADE 9': ['ST. FRANCIS', 'ST. PETER', 'ST. THOMAS'],
    'GRADE 10': ['ST. JOHN', 'ST. LUKE', 'ST. MATTHEW']
};

// Add an event listener to the Grade Level select element
document.getElementById('YearLevel').addEventListener('change', function() {
    // Get the selected grade level
    var gradeLevel = this.value;
    
    // Get the Section select element
    var sectionSelect = document.getElementById('Course');

    // Clear existing options
    sectionSelect.innerHTML = '';

    // Get the available sections for the selected grade level
    var availableSections = gradeSections[gradeLevel];

    // Create new option elements for the available sections
    availableSections.forEach(function(section) {
        var option = document.createElement('option');
        option.value = section;
        option.text = section;
        sectionSelect.appendChild(option);
    });
});
