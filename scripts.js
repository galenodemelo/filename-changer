const dropContainer = document.querySelector('#drop-container')
const fileInput = document.querySelector('#file-input')
const filesList = document.querySelector('#files-list')

// On dragover
dropContainer.addEventListener('dragover', (event) => {
	event.preventDefault()
	return false
})

// On drop
dropContainer.addEventListener('drop', (event) => {
	event.preventDefault()
	fileInput.files = event.dataTransfer.files
	listFiles()

	for (let i = 0, j = fileInput.files.length;
		i < j;
		i++) {

		// Read the file
		readFile(fileInput.files[i]).then((resolve) => {
			const data = resolve.target.result
			getText(data)
		})
	}

	return false
})

// List the files
function listFiles() {
	let htmlList = ''
	Array.from(fileInput.files).forEach((file) => {
		htmlList += `<li>${file.name}</li>`
	})

	document.getElementById('files-list').innerHTML = htmlList
}

// Read the files
function readFile(file) {
	return new Promise((resolve, reject) => {
		const reader = new FileReader()
		reader.readAsArrayBuffer(file)
		reader.onload = (fileContent) => {
			resolve(fileContent)
		}
	})
}

// Gets text from file
function getText(data) {
	pdfjsLib.GlobalWorkerOptions.workerSrc = './vendor/build/pdf.worker.js';
	pdfjsLib.getDocument(data).promise.then((pdf) => {
		pdf.getPage(1).then((page) => {
			page.getTextContent().then((textContent) => {

				const danfeCode = getDanfeCode(textContent)
				const customerName = getCustomerName(textContent)

				document.getElementById('danfe-code').value += `${danfeCode},`
				document.getElementById('customer-name').value += `${customerName},`
			})
		})
	})
}

function getDanfeCode(pdfText) {
	return pdfText.items[29].str.replace('Nº	', '')
}

function getCustomerName(pdfText) {
	const fullName = pdfText.items[49].str
	let finalCustomerName = fullName.replace(/\t/g, '_')

	if (finalCustomerName.indexOf('(') !== -1) {
		finalCustomerName = finalCustomerName.substr(0, finalCustomerName.indexOf('(') - 1)
	}

	return finalCustomerName.toLowerCase()
}