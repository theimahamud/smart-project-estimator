import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Dashboard() {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            <h3 className="text-2xl font-bold text-gray-900 mb-4">
                                Welcome to Smart Project Estimator! 🚀
                            </h3>
                            <p className="text-gray-600 mb-6">
                                An AI-powered tool that automatically analyzes your project requirements
                                and generates estimated costs, timelines, and resource breakdowns.
                            </p>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <Link
                                    href={route('estimates.create')}
                                    className="block p-6 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition"
                                >
                                    <h4 className="text-xl font-semibold mb-2">✨ New Estimate</h4>
                                    <p className="text-blue-100">
                                        Create a new project estimate using AI analysis
                                    </p>
                                </Link>

                                <Link
                                    href={route('estimates.index')}
                                    className="block p-6 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition"
                                >
                                    <h4 className="text-xl font-semibold mb-2">📊 My Estimates</h4>
                                    <p className="text-green-100">
                                        View and manage your saved estimates
                                    </p>
                                </Link>
                            </div>

                            <div className="border-t pt-6">
                                <h4 className="font-semibold text-gray-900 mb-3">How it works:</h4>
                                <ol className="list-decimal list-inside space-y-2 text-gray-600">
                                    <li>Describe your project requirements in natural language</li>
                                    <li>Select your preferred region and technology stack</li>
                                    <li>AI analyzes and extracts key features automatically</li>
                                    <li>Get instant cost, timeline, and resource estimates</li>
                                    <li>Review detailed breakdown by feature and role</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
