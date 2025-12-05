import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function Create({ regions, technologies }) {
    const { data, setData, post, processing, errors } = useForm({
        project_name: '',
        raw_requirements: '',
        region_id: '',
        selected_technologies: [],
        team_size: 3,
    });

    const [techByCategory, setTechByCategory] = useState(() => {
        const grouped = {};
        technologies.forEach(tech => {
            if (!grouped[tech.category]) {
                grouped[tech.category] = [];
            }
            grouped[tech.category].push(tech);
        });
        return grouped;
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('estimates.store'));
    };

    const toggleTechnology = (techId) => {
        const current = data.selected_technologies || [];
        if (current.includes(techId)) {
            setData('selected_technologies', current.filter(id => id !== techId));
        } else {
            setData('selected_technologies', [...current, techId]);
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Create New Estimate
                </h2>
            }
        >
            <Head title="Create Estimate" />

            <div className="py-12">
                <div className="mx-auto max-w-4xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <form onSubmit={handleSubmit} className="p-6 space-y-6">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Project Name
                                </label>
                                <input
                                    type="text"
                                    value={data.project_name}
                                    onChange={(e) => setData('project_name', e.target.value)}
                                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                    placeholder="E.g., E-commerce Platform"
                                    required
                                />
                                {errors.project_name && (
                                    <p className="text-red-600 text-sm mt-1">{errors.project_name}</p>
                                )}
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Project Requirements
                                </label>
                                <textarea
                                    value={data.raw_requirements}
                                    onChange={(e) => setData('raw_requirements', e.target.value)}
                                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                    rows="8"
                                    placeholder="Describe your project requirements in detail... Include features like user authentication, payment integration, admin dashboard, etc."
                                    required
                                />
                                {errors.raw_requirements && (
                                    <p className="text-red-600 text-sm mt-1">{errors.raw_requirements}</p>
                                )}
                                <p className="text-sm text-gray-500 mt-1">
                                    Minimum 50 characters. Be as detailed as possible for better estimates.
                                </p>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                        Region
                                    </label>
                                    <select
                                        value={data.region_id}
                                        onChange={(e) => setData('region_id', e.target.value)}
                                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                    >
                                        <option value="">Select Region</option>
                                        {regions.map((region) => (
                                            <option key={region.id} value={region.id}>
                                                {region.name} ({region.code})
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                        Team Size
                                    </label>
                                    <input
                                        type="number"
                                        value={data.team_size}
                                        onChange={(e) => setData('team_size', parseInt(e.target.value))}
                                        className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                        min="1"
                                        max="50"
                                    />
                                </div>
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-3">
                                    Technology Stack (Optional)
                                </label>
                                <div className="space-y-4">
                                    {Object.entries(techByCategory).map(([category, techs]) => (
                                        <div key={category}>
                                            <h4 className="font-medium text-gray-700 mb-2">{category}</h4>
                                            <div className="flex flex-wrap gap-2">
                                                {techs.map((tech) => (
                                                    <button
                                                        key={tech.id}
                                                        type="button"
                                                        onClick={() => toggleTechnology(tech.id)}
                                                        className={`px-3 py-1 rounded-full text-sm transition ${
                                                            (data.selected_technologies || []).includes(tech.id)
                                                                ? 'bg-blue-600 text-white'
                                                                : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                                                        }`}
                                                    >
                                                        {tech.name}
                                                    </button>
                                                ))}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>

                            <div className="flex justify-between items-center pt-4 border-t">
                                <a
                                    href={route('estimates.index')}
                                    className="text-gray-600 hover:text-gray-800"
                                >
                                    Cancel
                                </a>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
                                >
                                    {processing ? 'Generating...' : 'Generate Estimate'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

