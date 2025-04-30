<?php

include 'db.php';

// Pending Registration Count
$sql = "SELECT COUNT(*) FROM reg_hist WHERE approval='pending'";
$result = $conn->query($sql);
$row = $result->fetch_row();
$reg_pending_count = $row[0];

// Registered Students
$sql = "SELECT COUNT(id) FROM regd_studs";
$result = $conn->query($sql);
$row = $result->fetch_row();
$registered_count = $row[0];

// Listed Companies
$sql = "SELECT COUNT(DISTINCT name) FROM companies";
$result = $conn->query($sql);
$row = $result->fetch_row();
$companies_count = $row[0];

// Posted Jobs
$sql = "SELECT COUNT(*) FROM companies";
$result = $conn->query($sql);
$row = $result->fetch_row();
$jobs_count = $row[0];

// Job Applications
$sql = "SELECT COUNT(*) FROM applications";
$result = $conn->query($sql);
$row = $result->fetch_row();
$applications_count = $row[0];

// Selected Applications
$sql = "SELECT COUNT(*) FROM applications";
$result = $conn->query($sql);
$row = $result->fetch_row();
$selected_applications_count = $row[0];

?>


<style>
	/* Override the default header styling */
	header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 32px;
		padding: 16px 24px;
		background: #ffffff;
		border-radius: 12px;
		box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
	}

	header h1 {
		font-size: 28px;
		font-weight: 700;
		color: #1a1a1a;
		margin: 0;
		padding: 0;
	}

	.user-profile {
		display: flex;
		align-items: center;
		gap: 20px;
	}

	.user-profile a,
	.user-profile i {
		color: #2c2c2c;
		font-size: 20px;
		transition: all 0.3s ease;
		padding: 8px;
		border-radius: 8px;
		background: rgba(0, 0, 0, 0.05);
	}

	.user-profile a:hover,
	.user-profile i:hover {
		background: rgba(0, 0, 0, 0.08);
		transform: translateY(-2px);
	}

	.notification-count {
		background: #dc3545;
		color: white;
		border-radius: 50%;
		padding: 2px 6px;
		font-size: 12px;
		position: absolute;
		top: -5px;
		right: -5px;
	}

	.stats-grid {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 24px;
		padding: 0;
	}

	.stat-card {
		background: #ffffff;
		border-radius: 16px;
		padding: 32px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		transition: all 0.3s ease;
		border: 1px solid rgba(0, 0, 0, 0.08);
		box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
		position: relative;
		overflow: hidden;
	}

	.stat-card::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		height: 4px;
		background: linear-gradient(90deg, #2c2c2c, #4a4a4a);
		opacity: 0;
		transition: opacity 0.3s ease;
	}

	.stat-card:hover {
		transform: translateY(-4px);
		box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
	}

	.stat-card:hover::before {
		opacity: 1;
	}

	.stat-info {
		flex-grow: 1;
	}

	.stat-info h3 {
		color: #666666;
		font-size: 16px;
		font-weight: 500;
		margin: 0 0 12px 0;
		letter-spacing: 0.3px;
	}

	.stat-info p {
		color: #1a1a1a;
		font-size: 36px;
		font-weight: 700;
		margin: 0;
		line-height: 1;
	}

	.stat-icon {
		font-size: 32px;
		margin-left: 24px;
		width: 64px;
		height: 64px;
		display: flex;
		align-items: center;
		justify-content: center;
		border-radius: 16px;
		transition: all 0.3s ease;
	}

	.stat-icon.blue {
		color: #2c2c2c;
		background: linear-gradient(135deg, rgba(0, 0, 0, 0.05) 0%, rgba(0, 0, 0, 0.08) 100%);
	}

	.stat-card:hover .stat-icon.blue {
		background: linear-gradient(135deg, rgba(0, 0, 0, 0.08) 0%, rgba(0, 0, 0, 0.12) 100%);
		transform: scale(1.05);
	}

	@media (max-width: 768px) {
		header {
			flex-direction: column;
			gap: 16px;
			text-align: center;
			padding: 20px;
		}

		.stats-grid {
			grid-template-columns: 1fr;
			gap: 20px;
		}

		.stat-card {
			padding: 24px;
		}

		.stat-info h3 {
			font-size: 15px;
		}

		.stat-info p {
			font-size: 32px;
		}

		.stat-icon {
			font-size: 28px;
			width: 56px;
			height: 56px;
		}
	}
</style>

<main class="main-content">
	<header>
		<h1>Dashboard</h1>
		<div class="user-profile">
			<a href="home.php?page=announcements">
				<i class="fa fa-paper-plane" aria-hidden="true"></i>
			</a>

			<a href="home.php?page=notifications" style="position: relative;">
				<i class="fas fa-bell"></i>
				<?php if ($reg_pending_count > 0): ?>
					<span class="notification-count"><?php echo $reg_pending_count; ?></span>
				<?php endif; ?>
			</a>

			<i class="fas fa-user"></i>
			<div class="profile-dropdown">
				<div class="profile-info">
					<h4><?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Admin User'; ?></h4>
					<p><?php echo isset($_SESSION['email']) ? $_SESSION['email'] : 'admin@example.com'; ?></p>
				</div>
			</div>
		</div>
	</header>

	<div class="stats-grid">
		<div class="stat-card">
			<div class="stat-info">
				<h3>Listed Companies</h3>
				<p><?php echo $companies_count; ?></p>
			</div>
			<i class="fas fa-building stat-icon blue"></i>
		</div>

		<div class="stat-card">
			<div class="stat-info">
				<h3>Registered Students</h3>
				<p><?php echo $registered_count; ?></p>
			</div>
			<i class="fas fa-users stat-icon blue"></i>
		</div>

		<div class="stat-card">
			<div class="stat-info">
				<h3>Posted Jobs</h3>
				<p><?php echo $jobs_count; ?></p>
			</div>
			<i class="fas fa-file-alt stat-icon blue"></i>
		</div>

		<div class="stat-card">
			<div class="stat-info">
				<h3>Job Applications</h3>
				<p><?php echo $applications_count; ?></p>
			</div>
			<i class="fas fa-envelope stat-icon blue"></i>
		</div>
	</div>
</main>