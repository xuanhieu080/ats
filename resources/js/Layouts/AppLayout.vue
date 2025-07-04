<script setup>
import Icon, { UserOutlined } from '@ant-design/icons-vue';
import { ref, computed, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import flagVietnam from '@/Components/ant-components/icon/flagVietnam.vue';
import flagUS from '@/Components/ant-components/icon/flagUS.vue';
import FaceId from '@/Components/ant-components/icon/faceId.vue';
import DatabaseOutlined from '@/Components/ant-components/icon/databaseOutlined.vue';

defineProps({
	title: String,
});

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
	router.put(
		route('current-team.update'),
		{
			team_id: team.id,
		},
		{
			preserveState: false,
		},
	);
};

const logout = () => {
	router.post(route('logout'));
};
</script>

<template>
	<div>
		<Head :title="title" />

		<Banner />

		<div class="min-h-screen bg-gray-100">
			<nav class="bg-white border-b border-gray-100">
				<!-- Primary Navigation Menu -->
				<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
					<div class="flex justify-between h-16">
						<div class="flex">
							<!-- Logo -->
							<div class="shrink-0 flex items-center">
								<Link :href="route('recruitment-request')">
									<ApplicationMark class="block h-9 w-auto" />
								</Link>
							</div>

							<!-- Navigation Links -->
							<div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
								<NavLink :href="route('recruitment-request')" :active="route().current('recruitment-request')">
									Recruitment
								</NavLink>
								<NavLink :href="route('talent-pool')" :active="route().current('talent-pool')"> Talent pool </NavLink>
							</div>
						</div>

						<div class="hidden sm:flex sm:items-center sm:ms-6">
							<div class="ms-3 relative">
								<!-- Teams Dropdown -->
								<Dropdown v-if="$page.props.jetstream.hasTeamFeatures" align="right" width="60">
									<template #trigger>
										<span class="inline-flex rounded-md">
											<button
												type="button"
												class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
												{{ $page.props.auth.user.current_team.name }}

												<svg
													class="ms-2 -me-0.5 size-4"
													xmlns="http://www.w3.org/2000/svg"
													fill="none"
													viewBox="0 0 24 24"
													stroke-width="1.5"
													stroke="currentColor">
													<path
														stroke-linecap="round"
														stroke-linejoin="round"
														d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
												</svg>
											</button>
										</span>
									</template>

									<template #content>
										<div class="w-60">
											<!-- Team Management -->
											<div class="block px-4 py-2 text-xs text-gray-400">Manage Team</div>

											<!-- Team Settings -->
											<DropdownLink :href="route('teams.show', $page.props.auth.user.current_team)">
												Team Settings
											</DropdownLink>

											<DropdownLink v-if="$page.props.jetstream.canCreateTeams" :href="route('teams.create')">
												Create New Team
											</DropdownLink>

											<!-- Team Switcher -->
											<template v-if="$page.props.auth.user.all_teams.length > 1">
												<div class="border-t border-gray-200" />

												<div class="block px-4 py-2 text-xs text-gray-400">Switch Teams</div>

												<template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
													<form @submit.prevent="switchToTeam(team)">
														<DropdownLink as="button">
															<div class="flex items-center">
																<svg
																	v-if="team.id == $page.props.auth.user.current_team_id"
																	class="me-2 size-5 text-green-400"
																	xmlns="http://www.w3.org/2000/svg"
																	fill="none"
																	viewBox="0 0 24 24"
																	stroke-width="1.5"
																	stroke="currentColor">
																	<path
																		stroke-linecap="round"
																		stroke-linejoin="round"
																		d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
																</svg>

																<div>
																	{{ team.name }}
																</div>
															</div>
														</DropdownLink>
													</form>
												</template>
											</template>
										</div>
									</template>
								</Dropdown>
							</div>

							<!-- Settings Dropdown -->
							<div class="ms-3 relative flex items-center gap-2">
								<a-dropdown :placement="'bottomRight'">
									<a-button type="ghost" class="flex items-center justify-center text-xs text-gray-700">
										<template #icon>
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
												<!-- Icon from Material Design Icons by Pictogrammers - https://github.com/Templarian/MaterialDesign/blob/master/LICENSE -->
												<path
													fill="currentColor"
													d="M12 16c1.1 0 2 .9 2 2s-.9 2-2 2s-2-.9-2-2s.9-2 2-2m0-6c1.1 0 2 .9 2 2s-.9 2-2 2s-2-.9-2-2s.9-2 2-2m0-6c1.1 0 2 .9 2 2s-.9 2-2 2s-2-.9-2-2s.9-2 2-2M6 16c1.1 0 2 .9 2 2s-.9 2-2 2s-2-.9-2-2s.9-2 2-2m0-6c1.1 0 2 .9 2 2s-.9 2-2 2s-2-.9-2-2s.9-2 2-2m0-6c1.1 0 2 .9 2 2s-.9 2-2 2s-2-.9-2-2s.9-2 2-2m12 12c1.1 0 2 .9 2 2s-.9 2-2 2s-2-.9-2-2s.9-2 2-2m0-6c1.1 0 2 .9 2 2s-.9 2-2 2s-2-.9-2-2s.9-2 2-2m0-6c1.1 0 2 .9 2 2s-.9 2-2 2s-2-.9-2-2s.9-2 2-2" />
											</svg>
										</template>
									</a-button>
									<template #overlay>
										<a-menu>
											<a-menu-item>
												<a-button type="ghost" class="flex items-center justify-between w-[150px] p-0 gap-4">
													<template #icon>
														<FaceId class="!flex items-center text-xl text-[#C7C7C6]" />
													</template>
													<div class="flex-1 text-[var(--admin-menu-text)] font-medium text-left">Recruiters</div>
												</a-button>
											</a-menu-item>
											<a-menu-item>
												<a-button type="ghost" class="flex items-center justify-between w-full p-0 gap-4">
													<template #icon>
														<FaceId class="!flex items-center text-xl text-[#C7C7C6]" />
													</template>
													<div class="flex-1 text-[var(--admin-menu-text)] font-medium text-left">
														Interviewer
													</div>
												</a-button>
											</a-menu-item>
											<a-menu-item>
												<NavLink
													:no-link="true"
													:href="route('roles.index')"
													:active="route().current('roles.index')">
													<a-button type="ghost" class="flex items-center justify-between w-full p-0 gap-4">
														<template #icon>
															<DatabaseOutlined class="!flex items-center text-xl text-[#C7C7C6]" />
														</template>
														<div class="flex-1 text-[var(--admin-menu-text)] font-medium text-left">
															System Data
														</div>
													</a-button>
												</NavLink>
											</a-menu-item>
										</a-menu>
									</template>
								</a-dropdown>

								<Dropdown align="right" width="48">
									<template #trigger>
										<button
											v-if="$page.props.jetstream.managesProfilePhotos"
											class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
											<img
												class="size-8 rounded-full object-cover"
												:src="$page.props.auth.user.profile_photo_url"
												:alt="$page.props.auth.user.name" />
										</button>

										<span v-else class="inline-flex rounded-md">
											<a-dropdown v-if="false" :placement="'bottom'">
												<a-button type="ghost" class="p-0 mr-1 flex items-center my-auto">
													<Icon class="!flex items-center text-xl">
														<template #component>
															<flagVietnam />
														</template>
													</Icon>
												</a-button>
												<template #overlay>
													<a-menu>
														<a-menu-item>
															<div
																class="flex items-center justify-center p-0"
																@click="
																	() =>
																		$inertia.visit(
																			route('locale', {
																				locale: 'vi',
																			}),
																		)
																">
																<Icon class="!flex items-center text-xl">
																	<template #component>
																		<flagVietnam />
																	</template>
																</Icon>
															</div>
														</a-menu-item>
														<a-menu-item>
															<a rel="noopener noreferrer" href="#">
																<Icon class="!flex items-center text-xl">
																	<template #component>
																		<flagUS />
																	</template>
																</Icon>
															</a>
														</a-menu-item>
													</a-menu>
												</template>
											</a-dropdown>
											<button
												type="button"
												class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
												<!-- {{ $page.props.auth.user.name }} -->
												<img
													v-if="$page.props.auth.user.avatar"
													:src="$page.props.auth.user.avatar"
													class="w-9 h-9 rounded-full object-cover bg-gray-200"
													alt="" />
												<a-avatar v-else class="flex items-center justify-center">
													<template #icon><UserOutlined class="text-sm flex items-center" /></template>
												</a-avatar>

												<svg
													class="ms-2 -me-0.5 size-4"
													xmlns="http://www.w3.org/2000/svg"
													fill="none"
													viewBox="0 0 24 24"
													stroke-width="1.5"
													stroke="currentColor">
													<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
												</svg>
											</button>
										</span>
									</template>

									<template #content>
										<!-- Account Management -->
										<div class="block px-4 py-2 text-xs text-gray-400">Manage Account</div>

										<DropdownLink :href="route('profile.show')"> Profile </DropdownLink>

										<DropdownLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')">
											API Tokens
										</DropdownLink>

										<div class="border-t border-gray-200" />

										<!-- Authentication -->
										<form @submit.prevent="logout">
											<DropdownLink as="button"> Log Out </DropdownLink>
										</form>
									</template>
								</Dropdown>
							</div>
						</div>

						<!-- Hamburger -->
						<div class="-me-2 flex items-center sm:hidden">
							<button
								class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
								@click="showingNavigationDropdown = !showingNavigationDropdown">
								<svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
									<path
										:class="{
											hidden: showingNavigationDropdown,
											'inline-flex': !showingNavigationDropdown,
										}"
										stroke-linecap="round"
										stroke-linejoin="round"
										stroke-width="2"
										d="M4 6h16M4 12h16M4 18h16" />
									<path
										:class="{
											hidden: !showingNavigationDropdown,
											'inline-flex': showingNavigationDropdown,
										}"
										stroke-linecap="round"
										stroke-linejoin="round"
										stroke-width="2"
										d="M6 18L18 6M6 6l12 12" />
								</svg>
							</button>
						</div>
					</div>
				</div>

				<!-- Responsive Navigation Menu -->
				<div
					:class="{
						block: showingNavigationDropdown,
						hidden: !showingNavigationDropdown,
					}"
					class="sm:hidden">
					<div class="pt-2 pb-3 space-y-1">
						<ResponsiveNavLink :href="route('recruitment-request')" :active="route().current('recruitment-request')">
							Recruitment
						</ResponsiveNavLink>
					</div>

					<!-- Responsive Settings Options -->
					<div class="pt-4 pb-1 border-t border-gray-200">
						<div class="flex items-center px-4">
							<div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 me-3">
								<img
									class="size-10 rounded-full object-cover"
									:src="$page.props.auth.user.profile_photo_url"
									:alt="$page.props.auth.user.name" />
							</div>

							<div>
								<div class="font-medium text-base text-gray-800">
									{{ $page.props.auth.user.name }}
								</div>
								<div class="font-medium text-sm text-gray-500">
									{{ $page.props.auth.user.email }}
								</div>
							</div>
						</div>

						<div class="mt-3 space-y-1">
							<ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
								Profile
							</ResponsiveNavLink>

							<ResponsiveNavLink
								v-if="$page.props.jetstream.hasApiFeatures"
								:href="route('api-tokens.index')"
								:active="route().current('api-tokens.index')">
								API Tokens
							</ResponsiveNavLink>

							<!-- Authentication -->
							<form method="POST" @submit.prevent="logout">
								<ResponsiveNavLink as="button"> Log Out </ResponsiveNavLink>
							</form>

							<!-- Team Management -->
							<template v-if="$page.props.jetstream.hasTeamFeatures">
								<div class="border-t border-gray-200" />

								<div class="block px-4 py-2 text-xs text-gray-400">Manage Team</div>

								<!-- Team Settings -->
								<ResponsiveNavLink
									:href="route('teams.show', $page.props.auth.user.current_team)"
									:active="route().current('teams.show')">
									Team Settings
								</ResponsiveNavLink>

								<ResponsiveNavLink
									v-if="$page.props.jetstream.canCreateTeams"
									:href="route('teams.create')"
									:active="route().current('teams.create')">
									Create New Team
								</ResponsiveNavLink>

								<!-- Team Switcher -->
								<template v-if="$page.props.auth.user.all_teams.length > 1">
									<div class="border-t border-gray-200" />

									<div class="block px-4 py-2 text-xs text-gray-400">Switch Teams</div>

									<template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
										<form @submit.prevent="switchToTeam(team)">
											<ResponsiveNavLink as="button">
												<div class="flex items-center">
													<svg
														v-if="team.id == $page.props.auth.user.current_team_id"
														class="me-2 size-5 text-green-400"
														xmlns="http://www.w3.org/2000/svg"
														fill="none"
														viewBox="0 0 24 24"
														stroke-width="1.5"
														stroke="currentColor">
														<path
															stroke-linecap="round"
															stroke-linejoin="round"
															d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
													</svg>
													<div>{{ team.name }}</div>
												</div>
											</ResponsiveNavLink>
										</form>
									</template>
								</template>
							</template>
						</div>
					</div>
				</div>
			</nav>

			<!-- Page Heading -->
			<header v-if="$slots.header" class="bg-white shadow">
				<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
					<slot name="header" />
				</div>
			</header>

			<!-- Page Content -->
			<main>
				<slot />
			</main>
		</div>
	</div>
</template>
<style lang="scss">
.anticon {
	vertical-align: middle;
}
</style>
