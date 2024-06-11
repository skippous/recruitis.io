<template>
  <div>
    <h1>Job Listings</h1>
    <div v-if="loading" class="alert alert-info">Loading...</div>
    <div v-if="error" class="alert alert-danger">{{ error }}</div>
    <div v-if="jobs.length === 0 && !loading" class="alert alert-info">No job listings available at the moment.</div>
    <div v-else class="list-group">
      <div v-for="job in jobs" :key="job.jobId" class="list-group-item">
        <h5>{{ job.title }}</h5>
        <small class="text-muted">{{ new Date(job.dateCreated).toLocaleString() }}</small><br>
        <div v-if="job.salary">
          <small class="text-muted">Salary: {{ job.salary.min }} - {{ job.salary.max }} {{ job.salary.currency }} per {{ job.salary.unit }}</small><br>
        </div>
      </div>
    </div>
    <nav aria-label="Page navigation example">
      <ul class="pagination">
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
          <a class="page-link" href="#" @click.prevent="fetchJobs(currentPage - 1)">Previous</a>
        </li>
        <li v-for="page in totalPages" :key="page" class="page-item" :class="{ active: page === currentPage }">
          <a class="page-link" href="#" @click.prevent="fetchJobs(page)">{{ page }}</a>
        </li>
        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
          <a class="page-link" href="#" @click.prevent="fetchJobs(currentPage + 1)">Next</a>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
export default {
  data() {
    return {
      jobs: [],
      currentPage: 1,
      totalPages: 1,
      loading: false,
      error: null,
    };
  },
  methods: {
    fetchJobs(page = 1) {
      this.loading = true;
      this.error = null;
      fetch(`/api/jobs?page=${page}`)
          .then(response => response.json())
          .then(data => {
            this.jobs = data.jobs;
            this.totalPages = Math.ceil(data.jobs_total_count / 5); // Assuming 5 jobs per page
            this.currentPage = page;
            this.loading = false;
          })
          .catch(error => {
            this.error = 'Failed to fetch jobs';
            this.loading = false;
          });
    }
  },
  created() {
    this.fetchJobs();
  }
};
</script>
