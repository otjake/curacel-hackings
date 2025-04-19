<template>
  <PlainLayout title="Document Context AI">
    <div class="px-4 py-12 min-h-screen bg-gray-50 sm:px-6 lg:px-8">
      <div class="mx-auto max-w-3xl">
        <div class="mb-8 text-center">
          <h1 class="text-3xl font-bold text-gray-900">Document Context AI</h1>
          <p class="mt-2 text-sm text-gray-600">
            Upload multiple documents and leverage their context to enhance your work
          </p>
        </div>

        <div class="p-6 bg-white rounded-lg shadow">
          <!-- Document Upload Area -->
          <div class="p-6 text-center rounded-lg border-2 border-gray-300 border-dashed">
            <div class="space-y-4">
              <div class="flex justify-center items-center">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
              </div>
              <div class="text-sm text-gray-600">
                <label for="file-upload" class="relative font-medium text-indigo-600 bg-white rounded-md cursor-pointer hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                  <span>Upload documents</span>
                  <input id="file-upload" name="file-upload" type="file" class="sr-only" multiple @change="handleFileUpload">
                </label>
                <p class="pl-1">or drag and drop</p>
              </div>
              <p class="text-xs text-gray-500">
                PDF, DOCX, TXT up to 10MB
              </p>
            </div>
          </div>

          <!-- Uploaded Documents List -->
          <div v-if="uploadedDocuments.length > 0" class="mt-6">
            <h3 class="mb-4 text-lg font-medium text-gray-900">Uploaded Documents</h3>
            <ul class="divide-y divide-gray-200">
              <li v-for="(doc, index) in uploadedDocuments" :key="index" class="flex justify-between items-center py-4">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-gray-900">{{ doc.name }}</p>
                    <p class="text-sm text-gray-500">{{ formatFileSize(doc.size) }}</p>
                    <div v-if="doc.content" class="mt-2">
                      <p class="text-sm text-gray-600">Content extracted</p>
                      <button
                        @click="showContent(doc)"
                        class="mt-1 text-xs text-indigo-600 hover:text-indigo-900"
                      >
                        View Content
                      </button>
                    </div>
                  </div>
                </div>
                <div class="flex items-center space-x-2">
                  <button
                    v-if="!doc.content"
                    @click="extractContent(doc)"
                    class="text-indigo-600 hover:text-indigo-900"
                    :disabled="isExtracting"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                  </button>
                  <button @click="removeDocument(index)" class="text-red-600 hover:text-red-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </li>
            </ul>
          </div>

          <!-- Document Selection -->
          <div class="mb-6">
            <label class="block mb-2 text-sm font-medium text-gray-700">Select Documents for Processing</label>
            <v-select
              v-model="selectedDocuments"
              :options="existingDocuments"
              :reduce="doc => doc.id"
              label="name"
              multiple
              placeholder="Select documents to process..."
              class="w-full"
            ></v-select>
          </div>

          <!-- Prompt Input -->
          <div v-if="uploadedDocuments.length > 0" class="mt-6">
            <label for="prompt" class="block text-sm font-medium text-gray-700">What would you like to do with these documents?</label>
            <div class="mt-1">
              <textarea
                id="prompt"
                v-model="prompt"
                rows="3"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                placeholder="Example: Compare the content of these documents and highlight the key differences..."
              ></textarea>
            </div>
          </div>

          <!-- Analysis Result -->
          <div v-if="analysisResult" class="mt-6">
            <h3 class="mb-2 text-lg font-medium text-gray-900">Analysis Result</h3>
            <div class="p-4 bg-gray-50 rounded-lg">
              <div class="max-w-none prose">
                <div v-html="analysisResult"></div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-end mt-6 space-x-3">
            <button
              @click="clearAll"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-md border border-gray-300 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Clear All
            </button>
            <button
              @click="processDocuments"
              :disabled="!prompt || isProcessing"
              class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md border border-transparent shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="isProcessing">Processing...</span>
              <span v-else>Process Documents</span>
            </button>
          </div>
        </div>

        <!-- Loading Overlay -->
        <div v-if="isProcessing || isExtracting" class="flex fixed inset-0 justify-center items-center bg-gray-500 bg-opacity-75">
          <div class="p-6 text-center bg-white rounded-lg shadow-xl">
            <div class="mx-auto mb-4 w-12 h-12 rounded-full border-b-2 border-indigo-600 animate-spin"></div>
            <p class="font-medium text-gray-700">
              {{ isProcessing ? currentLoadingMessage : 'Extracting content...' }}
            </p>
            <p class="mt-2 text-sm text-gray-500">
              {{ isProcessing ? 'This may take a few moments...' : 'Please wait while we extract the content...' }}
            </p>
          </div>
        </div>

        <!-- Content Modal -->
        <div v-if="selectedDocument" class="flex fixed inset-0 justify-center items-center p-4 bg-gray-500 bg-opacity-75">
          <div class="bg-white rounded-lg max-w-2xl w-full max-h-[80vh] overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b">
              <h3 class="text-lg font-medium text-gray-900">{{ selectedDocument.name }}</h3>
              <button @click="selectedDocument = null" class="text-gray-400 hover:text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            <div class="p-4 overflow-y-auto max-h-[60vh]">
              <div v-html="selectedDocument.content"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </PlainLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import PlainLayout from '@/Layouts/PlainLayout.vue'
import axios from 'axios'
import 'vue-select/dist/vue-select.css'

const props = defineProps({
  documents: Array
})

const uploadedDocuments = ref([])
const existingDocuments = ref([])
const selectedDocuments = ref([])
const prompt = ref('')
const isProcessing = ref(false)
const isExtracting = ref(false)
const selectedDocument = ref(null)
const analysisResult = ref(null)
const currentLoadingMessage = ref('')
const loadingMessages = [
  'Analyzing document structure...',
  'Extracting key information...',
  'Identifying patterns and relationships...',
  'Processing context and meaning...',
  'Generating insights...',
  'Almost there...'
]

// Watch for changes in selected documents
watch(selectedDocuments, (newSelected) => {
  // Add newly selected documents to uploadedDocuments if they're not already there
  newSelected.forEach(docId => {
    const doc = existingDocuments.value.find(d => d.id === docId)
    if (doc && !uploadedDocuments.value.some(u => u.id === docId)) {
      uploadedDocuments.value.push({
        ...doc,
        name: doc.name,
        content: doc.content || null
      })
    }
  })
}, { deep: true })

const fetchDocuments = async () => {
  try {
    existingDocuments.value = props.documents?.map(doc => ({
      id: doc.id,
      name: doc.file_name,
      size: doc.file_size,
      content: doc.file_extract || null,
      path: doc.file_path
    }))
    console.log("exix",existingDocuments.value)
  } catch (error) {
    console.error('Error fetching documents:', error)
  }
}

onMounted(() => {
    fetchDocuments()
})

const handleFileUpload = async (event) => {
  const files = Array.from(event.target.files)
  const formData = new FormData()

  files.forEach(file => {
    formData.append('documents[]', file)
  })

  isProcessing.value = true
  try {
    const response = await axios.post(route('document-context.upload'), formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    // Append new files to existing uploadedDocuments
    uploadedDocuments.value = [...uploadedDocuments.value, ...response.data.files]
  } catch (error) {
    console.error('Error uploading files:', error)
    alert(error.response?.data?.message || 'Failed to upload files. Please try again.')
  } finally {
    isProcessing.value = false
  }
}

const removeDocument = (index) => {
  uploadedDocuments.value.splice(index, 1)
}

const clearAll = () => {
  uploadedDocuments.value = []
  selectedDocuments.value = []
  prompt.value = ''
  analysisResult.value = null
}

const formatFileSize = (bytes) => {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

const processDocuments = async () => {
  isProcessing.value = true
  analysisResult.value = null
  let messageIndex = 0

  // Start the message rotation
  const messageInterval = setInterval(() => {
    currentLoadingMessage.value = loadingMessages[messageIndex]
    messageIndex = (messageIndex + 1) % loadingMessages.length
  }, 2000)

  try {
    // Merge all documents (uploaded and selected from existing)
    const allDocuments = [
      ...uploadedDocuments.value,
      ...existingDocuments.value.filter(doc => 
        selectedDocuments.value.includes(doc.id)
      )
    ]

    // Check if we have at least 2 documents and a prompt
    if (allDocuments.length < 2 || !prompt.value) {
      alert('Please select or upload at least 2 documents and provide a prompt')
      return
    }

    // Check if all documents have content
    const documentsWithContent = allDocuments.filter(doc => doc.content)
    if (documentsWithContent.length !== allDocuments.length) {
      alert('Please extract content from all documents before processing')
      return
    }

    // Remove duplicates based on document ID
    const uniqueDocuments = [...new Map(documentsWithContent.map(doc => [doc.id, doc])).values()]

    const response = await axios.post('/api/document-context/process', {
      document_ids: uniqueDocuments.map(doc => doc.id),
      prompt: prompt.value
    })

    // Set the analysis result
    analysisResult.value = response.data.result
  } catch (error) {
    console.error('Error processing documents:', error)
    if (error.response?.data?.errors) {
        const errorMessages = Object.values(error.response.data.errors).flat()
        alert(errorMessages.join('\n'))
    }
    alert(error.response?.data?.message || 'Failed to process documents. Please try again.')
  } finally {
    clearInterval(messageInterval)
    currentLoadingMessage.value = ''
    isProcessing.value = false
  }
}

const extractContent = async (doc) => {
  isExtracting.value = true
  try {
    const response = await axios.post('/api/document-context/extract-content', {
      path: doc.path,
      document_id: doc.id
    })

    // Update the document with extracted content
    const index = uploadedDocuments.value.findIndex(d => d.path === doc.path)
    if (index !== -1) {
      uploadedDocuments.value[index].content = response.data.extractedContent
    }
  } catch (error) {
    console.error('Error extracting content:', error)
    alert(error.response?.data?.message || 'Failed to extract content. Please try again.')
  } finally {
    isExtracting.value = false
  }
}

const showContent = (doc) => {
  selectedDocument.value = doc
}
</script>
